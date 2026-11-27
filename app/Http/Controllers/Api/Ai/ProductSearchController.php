<?php

namespace App\Http\Controllers\Api\Ai;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Validation\Rule;
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
            'gender' => ['nullable', Rule::in(Product::GENDERS)],
            'sort' => ['nullable', Rule::in(ProductSearchService::SORTS)],
        ]);

        $result = $this->productSearchService->search(
            keywords: $validated['keywords'] ?? null,
            category: $validated['category'] ?? null,
            subcategory: $validated['subcategory'] ?? null,
            brand: $validated['brand'] ?? null,
            minPrice: $validated['min_price'] ?? null,
            maxPrice: $validated['max_price'] ?? null,
            productType: $validated['product_type'] ?? null,
            gender: $validated['gender'] ?? null,
            sort: $validated['sort'] ?? null,
        );

        return response()->json([
            'success' => true,
            'query' => $validated,
            'count' => $result['products']->count(),
            'data' => $result['products'],
            'unmatched_filters' => (object) $result['unmatched_filters'],
        ]);
    }
}
