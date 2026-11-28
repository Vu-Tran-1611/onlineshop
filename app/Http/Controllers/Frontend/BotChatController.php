<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Services\Ai\ProductSearchService;
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

    public function __construct(
        private readonly ChatMemoryService $memory,
        private readonly ProductSearchService $productSearch,
    ) {
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
                $badgeCategories = $this->loadBadgeCategories('name', $response->json('narrow_categories', []));

                $this->memory->append($conversationId, Auth::id(), $conversation, [
                    $this->message('user', $validated['message']),
                    $this->message('assistant', $answer, $products, $badgeCategories),
                ]);
                $this->queueConversationCookie($conversationId);

                return response()->json([
                    'answer' => $answer,
                    'answer_html' => $this->renderAnswer($answer),
                    'products_html' => $this->renderProducts($products),
                    'badges_html' => $this->renderBadges($badgeCategories),
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
            $badgeCategories = $this->loadBadgeCategories('id', $message['badge_category_ids'] ?? []);

            return [
                'role' => $message['role'],
                'content' => $message['content'],
                'answer_html' => $message['role'] === 'assistant' ? $this->renderAnswer($message['content']) : null,
                'products_html' => $this->renderProducts($products),
                'badges_html' => $this->renderBadges($badgeCategories),
            ];
        }, $conversation['messages'] ?? []);

        return response()->json(['messages' => $messages]);
    }

    // Show a clicked subcategory badge without asking the AI, and save it to the conversation
    public function browse(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|integer',
            'sub_category_id' => 'nullable|integer',
        ]);

        $category = Category::where('status', 1)->find($validated['category_id']);
        $subCategory = isset($validated['sub_category_id'])
            ? SubCategory::where('status', 1)->where('category_id', $category?->id)->find($validated['sub_category_id'])
            : null;

        if (!$category || (isset($validated['sub_category_id']) && !$subCategory)) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        $found = $this->productSearch->search(category: $category->name, subcategory: $subCategory?->name);
        $products = $this->loadProducts($found['products']->pluck('id')->all());

        // Same text as the button the user clicked, so the history reads the same
        $question = $subCategory ? "Show me {$subCategory->name}" : "Show me All {$category->name}";
        $label = $subCategory ? "{$subCategory->name} from {$category->name}" : "{$category->name} products";
        $answer = $products->isEmpty()
            ? "Sorry, there are no {$label} right now."
            : "Here are some {$label}.";

        [$conversationId, $conversation] = $this->resolveConversation($request);
        $this->memory->append($conversationId, Auth::id(), $conversation, [
            $this->message('user', $question),
            $this->message('assistant', $answer, $products),
        ]);
        $this->queueConversationCookie($conversationId);

        return response()->json([
            'answer' => $answer,
            'answer_html' => $this->renderAnswer($answer),
            'products_html' => $this->renderProducts($products),
            'badges_html' => '',
        ]);
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

    // Build a stored message; assistant messages keep the products and badges they showed
    private function message(string $role, string $content, ?Collection $products = null, ?Collection $badgeCategories = null): array
    {
        $products ??= collect();
        $badgeCategories ??= collect();

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
            'badge_category_ids' => $badgeCategories->pluck('id')->all(),
            // Offered subcategories, so a typed "jeans" can be understood
            'badges_note' => $badgeCategories->map(fn (Category $category) => sprintf(
                '%s: %s',
                $category->name,
                $category->subCategories->pluck('name')->implode(', ')
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

    // Render subcategory badges, or nothing when there are none
    private function renderBadges(Collection $categories): string
    {
        return $categories->isEmpty()
            ? ''
            : view('frontend.sections.partials.bot_badges', compact('categories'))->render();
    }

    // Load active categories by name or ID, with their subcategories that have products
    private function loadBadgeCategories(string $column, mixed $values): Collection
    {
        $values = collect(is_array($values) ? $values : [])->filter(fn ($value) => is_scalar($value))->unique()->values();
        if ($values->isEmpty()) {
            return collect();
        }

        $hasProducts = fn ($query) => $query->where('status', 1)->where('is_approved', 1);

        return Category::where('status', 1)
            ->whereIn($column, $values)
            ->with(['subCategories' => fn ($query) => $query
                ->where('status', 1)
                ->whereHas('products', $hasProducts)
                ->orderBy('id')])
            ->get()
            ->filter(fn (Category $category) => $category->subCategories->isNotEmpty())
            ->sortBy(fn (Category $category) => $values->search($category->{$column}))
            ->values();
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
