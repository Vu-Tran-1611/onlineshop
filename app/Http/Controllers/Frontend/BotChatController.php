<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Chat\ChatMemoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class BotChatController extends Controller
{
    // Match MAX_PRODUCTS in the ML product search agent
    private const MAX_PRODUCTS = 12;

    public function __construct(private readonly ChatMemoryService $memory)
    {
    }

    // Answer a message using the conversation history
    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $baseUrl = config('services.fastapi_ai.url') ?: config('services.python_api.url');
        if (empty($baseUrl)) {
            return response()->json([
                'message' => 'FastAPI URL is not configured.',
            ], 500);
        }

        [$conversationId, $conversation] = $this->resolveConversation($request);
        $endpoint = rtrim($baseUrl, '/') . '/chat';

        try {
            $response = Http::timeout(100)
                ->acceptJson()
                // Query parameter kept for ML service versions without history support
                ->withQueryParameters(['questions' => $validated['message']])
                ->post($endpoint, [
                    'message' => $validated['message'],
                    'history' => $this->memory->historyForAi($conversation),
                ]);

            if ($response->successful()) {
                $answer = trim((string) $response->json('response', ''));
                $products = $this->loadProducts($response->json('products', []));

                $this->memory->append($conversationId, Auth::id(), $conversation, [
                    $this->message('user', $validated['message']),
                    $this->message('assistant', $answer, $products),
                ]);
                $this->queueConversationCookie($conversationId);

                return response()->json([
                    'answer' => $answer,
                    'answer_html' => $this->renderAnswer($answer),
                    'products_html' => $this->renderProducts($products),
                ]);
            }

            $details = $response->json();
            if ($details === null) {
                $details = $response->body();
            }

            return response()->json([
                'message' => 'Bot service returned an error.',
                'details' => $details,
            ], $response->status());
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Bot service is temporarily unavailable.',
                'error' => $e->getMessage(),
            ], 502);
        }
    }

    // Return the current conversation for the chat box to redraw
    public function history(Request $request): JsonResponse
    {
        $id = $request->cookie(ChatMemoryService::COOKIE);
        $conversation = $this->memory->isValidId($id) ? $this->memory->load($id, Auth::id()) : null;

        $messages = array_map(function (array $message) {
            $products = $this->loadProducts($message['product_ids'] ?? []);

            return [
                'role' => $message['role'],
                'content' => $message['content'],
                'answer_html' => $message['role'] === 'assistant' ? $this->renderAnswer($message['content']) : null,
                'products_html' => $this->renderProducts($products),
            ];
        }, $conversation['messages'] ?? []);

        return response()->json(['messages' => $messages]);
    }

    // Delete the conversation and forget its cookie
    public function reset(Request $request): JsonResponse
    {
        $id = $request->cookie(ChatMemoryService::COOKIE);
        if ($this->memory->isValidId($id) && $this->memory->load($id, Auth::id()) !== null) {
            $this->memory->clear($id);
        }
        Cookie::queue(Cookie::forget(ChatMemoryService::COOKIE));

        return response()->json(['reset' => true]);
    }

    // Find this browser's conversation, or start a new one
    private function resolveConversation(Request $request): array
    {
        $id = $request->cookie(ChatMemoryService::COOKIE);
        if ($this->memory->isValidId($id)) {
            $conversation = $this->memory->load($id, Auth::id());
            if ($conversation !== null) {
                return [$id, $conversation];
            }
        }

        // Expired, missing or another account's conversation: start a new one, never reuse the ID
        return [$this->memory->newId(), null];
    }

    // Refresh the conversation cookie for another 7 days
    private function queueConversationCookie(string $id): void
    {
        Cookie::queue(
            ChatMemoryService::COOKIE,
            $id,
            ChatMemoryService::TTL_MINUTES,
            '/',
            null,
            request()->secure(),
            true,
            false,
            'lax'
        );
    }

    // Build a stored message; assistant messages keep the products they showed
    private function message(string $role, string $content, ?Collection $products = null): array
    {
        $products ??= collect();

        return [
            'role' => $role,
            'content' => $content,
            'product_ids' => $products->pluck('id')->all(),
            // Short list so follow-ups like "the second one" can be understood
            'products_note' => $products->values()->map(fn (Product $product, int $i) => sprintf(
                '%d. %s $%s (id %d)',
                $i + 1,
                $product->name,
                checkSale($product) ? $product->offer_price : $product->price,
                $product->id
            ))->implode('; ') ?: null,
            'at' => now()->toIso8601String(),
        ];
    }

    // Render the answer as Markdown with raw HTML escaped
    private function renderAnswer(string $answer): string
    {
        return Str::markdown($answer, [
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]);
    }

    // Render product cards, or nothing when there are no products
    private function renderProducts(Collection $products): string
    {
        return $products->isEmpty()
            ? ''
            : view('frontend.sections.partials.bot_product_cards', compact('products'))->render();
    }

    // Load active products in the order the agent chose
    private function loadProducts(mixed $ids): Collection
    {
        $ids = collect(is_array($ids) ? $ids : [])
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->take(self::MAX_PRODUCTS)
            ->values();

        if ($ids->isEmpty()) {
            return collect();
        }

        return Product::with(['brand:id,name', 'category:id,name'])
            ->whereIn('id', $ids)
            ->where('status', 1)
            ->where('is_approved', 1)
            ->get()
            ->sortBy(fn (Product $product) => $ids->search($product->id))
            ->values();
    }
}
