<?php

namespace App\Services\Ai;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProductSearchService
{
    public function __construct(private readonly CatalogFilterService $catalogFilterService)
    {
    }

    // Search products and report filters that match nothing
    public function search(
        ?string $keywords = null,
        ?string $category = null,
        ?string $subcategory = null,
        ?string $brand = null,
        ?float $minPrice = null,
        ?float $maxPrice = null,
        ?string $productType = null
    ): array
    {
        $categoryIds = $this->resolveCategoryIds($category);
        $subCategoryIds = $this->resolveSubCategoryIds($subcategory, $categoryIds);
        $brandIds = $this->resolveBrandIds($brand);

        $unmatched = $this->unmatchedFilters([
            'category' => [$category, $categoryIds],
            'subcategory' => [$subcategory, $subCategoryIds],
            'brand' => [$brand, $brandIds],
        ]);

        // Stop when a filter matches nothing
        if (!empty($unmatched)) {
            return ['products' => collect(), 'unmatched_filters' => $unmatched];
        }

        return ['products' => $this->query(
            $keywords, $categoryIds, $subCategoryIds, $brandIds, $minPrice, $maxPrice, $productType
        ), 'unmatched_filters' => []];
    }

    // Query active products with the resolved filters
    private function query(
        ?string $keywords,
        array $categoryIds,
        array $subCategoryIds,
        array $brandIds,
        ?float $minPrice,
        ?float $maxPrice,
        ?string $productType
    ): Collection
    {
        return Product::query()
            ->with(['brand:id,name', 'category:id,name', 'subCategory:id,name'])
            ->select([
                'id',
                'name',
                'category_id',
                'sub_category_id',
                'brand_id',
                'price',
                'offer_price',
                'offer_start_price',
                'offer_end_price',
                'product_type',
                'short_description',
            ])
            ->where('status', 1)
            ->where('is_approved', 1)
            ->when(!empty($categoryIds), fn ($builder) => $builder->whereIn('category_id', $categoryIds))
            ->when(!empty($subCategoryIds), fn ($builder) => $builder->whereIn('sub_category_id', $subCategoryIds))
            ->when(!empty($brandIds), fn ($builder) => $builder->whereIn('brand_id', $brandIds))
            ->when($minPrice !== null, function ($builder) use ($minPrice) {
                $builder->whereRaw('COALESCE(offer_price, price) >= ?', [$minPrice]);
            })
            ->when($maxPrice !== null, function ($builder) use ($maxPrice) {
                $builder->whereRaw('COALESCE(offer_price, price) <= ?', [$maxPrice]);
            })
            ->when($productType, fn ($builder) => $builder->where('product_type', $productType))
            ->when($keywords, function ($builder) use ($keywords) {
                $builder->where(function ($subQuery) use ($keywords) {
                    $subQuery->where('name', 'like', '%' . $keywords . '%')
                        ->orWhere('short_description', 'like', '%' . $keywords . '%')
                        ->orWhere('long_description', 'like', '%' . $keywords . '%')
                        ->orWhere('seo_title', 'like', '%' . $keywords . '%')
                        ->orWhere('seo_description', 'like', '%' . $keywords . '%')
                        ->orWhere('sku', 'like', '%' . $keywords . '%');
                });
            })
            ->orderByDesc('id')
            ->limit(10)
            ->get()
            ->map(function (Product $product) {
                $onSale = checkSale($product);

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'brand' => $product->brand?->name,
                    'category' => $product->category?->name,
                    'subcategory' => $product->subCategory?->name,
                    'price' => $onSale ? $product->offer_price : $product->price,
                    'original_price' => $onSale ? $product->price : null,
                    'product_type' => $product->product_type,
                    'short_description' => Str::squish(strip_tags(str_replace('<', ' <', (string) $product->short_description))),
                ];
            });
    }

    // Build suggestions for filters that matched nothing
    private function unmatchedFilters(array $filters): array
    {
        $catalog = $this->catalogFilterService->filters();
        $names = [
            'category' => $catalog['categories'],
            'subcategory' => array_column($catalog['subcategories'], 'name'),
            'brand' => $catalog['brands'],
        ];

        $unmatched = [];

        foreach ($filters as $key => [$value, $ids]) {
            if ($value && empty($ids)) {
                $unmatched[$key] = [
                    'value' => $value,
                    'suggestions' => $this->catalogFilterService->suggest($value, $names[$key]),
                ];
            }
        }

        return $unmatched;
    }

    private function resolveCategoryIds(?string $category): array
    {
        if (!$category) {
            return [];
        }

        return Category::query()
            ->where('status', 1)
            ->where(function ($builder) use ($category) {
                $builder->where('name', 'like',  $category )
                    ->orWhere('slug', 'like',  $category );
            })
            ->pluck('id')
            ->all();
    }

    private function resolveSubCategoryIds(?string $subcategory, array $categoryIds = []): array
    {
        if (!$subcategory) {
            return [];
        }

        return SubCategory::query()
            ->where('status', 1)
            ->when(!empty($categoryIds), fn ($builder) => $builder->whereIn('category_id', $categoryIds))
            ->where(function ($builder) use ($subcategory) {
                $builder->where('name', 'like' ,$subcategory )
                    ->orWhere('slug', 'like' ,$subcategory );
            })
            ->pluck('id')
            ->all();
    }

    private function resolveBrandIds(?string $brand): array
    {
        if (!$brand) {
            return [];
        }

        return Brand::query()
            ->where('status', 1)
            ->where(function ($builder) use ($brand) {
                $builder->where('name', 'like', '%' . $brand . '%')
                    ->orWhere('slug', 'like', '%' . $brand . '%');
            })
            ->pluck('id')
            ->all();
    }
}
