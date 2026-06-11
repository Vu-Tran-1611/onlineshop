<?php

namespace App\Services\Ai;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Support\Collection;

class ProductSearchService
{
    public function search(
        ?string $keywords = null,
        ?string $category = null,
        ?string $subcategory = null,
        ?string $brand = null,
        ?float $minPrice = null,
        ?float $maxPrice = null,
        ?string $productType = null
    ): Collection
    {
        $categoryIds = $this->resolveCategoryIds($category);
        $subCategoryIds = $this->resolveSubCategoryIds($subcategory, $categoryIds);
        $brandIds = $this->resolveBrandIds($brand);

        return Product::query()
            ->select([
                'id',
                'name',
                'slug',
                'thumb_image',
                'category_id',
                'sub_category_id',
                'brand_id',
                'price',
                'offer_price',
                'product_type',
                'short_description',
                'seo_title',
                'seo_description',
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
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'thumb_image' => $product->thumb_image,
                    'price' => $product->price,
                    'offer_price' => $product->offer_price,
                    'product_type' => $product->product_type,
                    'short_description' => $product->short_description,
                    'category_id' => $product->category_id,
                    'sub_category_id' => $product->sub_category_id,
                    'brand_id' => $product->brand_id,
                    'seo_title' => $product->seo_title,
                    'seo_description' => $product->seo_description,
                    'url' => url('/product/' . $product->slug),
                ];
            });
    }

    private function resolveCategoryIds(?string $category): array
    {
        if (!$category) {
            return [];
        }

        return Category::query()
            ->where(function ($builder) use ($category) {
                $builder->where('name', 'like', '%' . $category . '%')
                    ->orWhere('slug', 'like', '%' . $category . '%');
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
            ->when(!empty($categoryIds), fn ($builder) => $builder->whereIn('category_id', $categoryIds))
            ->where(function ($builder) use ($subcategory) {
                $builder->where('name', 'like', '%' . $subcategory . '%')
                    ->orWhere('slug', 'like', '%' . $subcategory . '%');
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
            ->where(function ($builder) use ($brand) {
                $builder->where('name', 'like', '%' . $brand . '%')
                    ->orWhere('slug', 'like', '%' . $brand . '%');
            })
            ->pluck('id')
            ->all();
    }
}
