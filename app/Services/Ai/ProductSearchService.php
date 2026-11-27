<?php

namespace App\Services\Ai;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Services\Search\ProductSearchIndex;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProductSearchService
{
    private const LIMIT = 10;

    // Weight of meaning vs keywords in hybrid search (chat questions are descriptive)
    private const SEMANTIC_RATIO = 0.5;

    // Candidates fetched for gender searches before reordering by gender group
    private const GENDER_CANDIDATES = 40;

    public function __construct(
        private readonly CatalogFilterService $catalogFilterService,
        private readonly ProductSearchIndex $searchIndex,
    ) {
    }

    // Search products and report filters that match nothing
    public function search(
        ?string $keywords = null,
        ?string $category = null,
        ?string $subcategory = null,
        ?string $brand = null,
        ?float $minPrice = null,
        ?float $maxPrice = null,
        ?string $productType = null,
        ?string $gender = null
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
            $keywords, $categoryIds, $subCategoryIds, $brandIds, $minPrice, $maxPrice, $productType, $gender
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
        ?string $productType,
        ?string $gender
    ): Collection
    {
        $products = $this->searchIndex->enabled()
            ? $this->queryIndex($keywords, $categoryIds, $subCategoryIds, $brandIds, $minPrice, $maxPrice, $productType, $gender)
            : $this->queryDatabase($keywords, $categoryIds, $subCategoryIds, $brandIds, $minPrice, $maxPrice, $productType, $gender);

        return $products->map(function (Product $product) {
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
                'gender' => $product->gender,
                'short_description' => Str::squish(strip_tags(str_replace('<', ' <', (string) $product->short_description))),
            ];
        })->values();
    }

    // Search the Meilisearch index, keeping its relevance order
    private function queryIndex(
        ?string $keywords,
        array $categoryIds,
        array $subCategoryIds,
        array $brandIds,
        ?float $minPrice,
        ?float $maxPrice,
        ?string $productType,
        ?string $gender
    ): Collection
    {
        $filters = array_filter([
            $categoryIds ? ProductSearchIndex::in('category_id', $categoryIds) : null,
            $subCategoryIds ? ProductSearchIndex::in('sub_category_id', $subCategoryIds) : null,
            $brandIds ? ProductSearchIndex::in('brand_id', $brandIds) : null,
            $minPrice !== null ? '(' . ProductSearchIndex::activePrice('>=', $minPrice) . ')' : null,
            $maxPrice !== null ? '(' . ProductSearchIndex::activePrice('<=', $maxPrice) . ')' : null,
            $productType ? 'product_type = ' . json_encode($productType) : null,
            // Men/women also include unisex; untagged products are kept for the chatbot
            ProductSearchIndex::gender($gender, includeUntagged: true),
        ]);

        // Show newest first when there is nothing to rank by
        $sort = trim((string) $keywords) === '' ? ['id:desc'] : [];

        // One search; fetch extra candidates when gender groups will be reordered
        $limit = $gender ? self::GENDER_CANDIDATES : self::LIMIT;
        $ids = $this->searchIndex->ids($keywords, $filters, $sort, $limit, self::SEMANTIC_RATIO);

        $products = Product::with(['brand:id,name', 'category:id,name', 'subCategory:id,name'])
            ->whereIn('id', $ids)
            ->get();

        // Order by gender group (made for it, unisex, untagged), then by search relevance
        return $products
            ->sortBy(fn (Product $product) => [$this->genderRank($product, $gender), array_search($product->id, $ids)])
            ->take(self::LIMIT);
    }

    // Rank a product's gender for a gender search: 0 = exact, 1 = unisex, 2 = untagged
    private function genderRank(Product $product, ?string $gender): int
    {
        return match (true) {
            $gender === null, $product->gender === $gender => 0,
            $product->gender === 'unisex' => 1,
            default => 2,
        };
    }

    // Search MySQL with LIKE when no search index is configured
    private function queryDatabase(
        ?string $keywords,
        array $categoryIds,
        array $subCategoryIds,
        array $brandIds,
        ?float $minPrice,
        ?float $maxPrice,
        ?string $productType,
        ?string $gender
    ): Collection
    {
        return Product::query()
            ->with(['brand:id,name', 'category:id,name', 'subCategory:id,name'])
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
            // Keep untagged products, they are not specified for anyone
            ->when(Product::gendersFor($gender), fn ($builder, $values) => $builder->where(
                fn ($query) => $query->whereIn('gender', $values)->orWhereNull('gender')
            ))
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
            // Put products made for the gender first, then unisex, then untagged ones
            ->when(
                Product::gendersFor($gender),
                fn ($builder) => $builder->orderByRaw(
                    "CASE WHEN gender = ? THEN 0 WHEN gender = 'unisex' THEN 1 ELSE 2 END",
                    [$gender]
                )
            )
            ->orderByDesc('id')
            ->limit(self::LIMIT)
            ->get();
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
