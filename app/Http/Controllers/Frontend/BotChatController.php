<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class BotChatController extends Controller
{
    private const MAX_PRODUCTS = 10;

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

        $endpoint = rtrim($baseUrl, '/') . '/chat';

        try {
            $response = Http::timeout(100)
                ->acceptJson()
                ->withQueryParameters([
                    'questions' => $validated['message'],
                ])
                ->post($endpoint);

            if ($response->successful()) {
                $answer = trim((string) $response->json('response', ''));
                $products = $this->loadProducts($response->json('products', []));

                return response()->json([
                    'answer' => $answer,
                    // Escape raw HTML from the model
                    'answer_html' => Str::markdown($answer, [
                        'html_input' => 'escape',
                        'allow_unsafe_links' => false,
                    ]),
                    'products_html' => $products->isEmpty()
                        ? ''
                        : view('frontend.sections.partials.bot_product_cards', compact('products'))->render(),
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
