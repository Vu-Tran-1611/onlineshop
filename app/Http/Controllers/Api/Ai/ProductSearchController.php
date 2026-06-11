<?php

namespace App\Http\Controllers\Api\Ai;

use App\Http\Controllers\Controller;
use App\Services\Ai\ProductSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductSearchController extends Controller
{
    public function __construct(private readonly ProductSearchService $productSearchService)
    {
    }

    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'keywords' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'subcategory' => ['nullable', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
            'product_type' => ['nullable', 'string', 'max:50'],
        ]);

        $results = $this->productSearchService->search(
            keywords: $validated['keywords'] ?? null,
            category: $validated['category'] ?? null,
            subcategory: $validated['subcategory'] ?? null,
            brand: $validated['brand'] ?? null,
            minPrice: $validated['min_price'] ?? null,
            maxPrice: $validated['max_price'] ?? null,
            productType: $validated['product_type'] ?? null,
        );

        return response()->json([
            'success' => true,
            'query' => $validated,
            'count' => $results->count(),
            'data' => $results,
        ]);
    }
}
