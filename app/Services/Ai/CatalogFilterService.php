<?php

namespace App\Services\Ai;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CatalogFilterService
{
    private const CACHE_KEY = 'ai_catalog_filters';
    private const CACHE_TTL = 600;
    private const MIN_SCORE = 0.6;

    // Return active catalog filter names
    public function filters(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return [
                'categories' => Category::where('status', 1)->orderBy('name')->pluck('name')->all(),
                'subcategories' => SubCategory::where('status', 1)
                    ->with('category:id,name')
                    ->orderBy('name')
                    ->get(['name', 'category_id'])
                    ->map(fn ($sub) => ['name' => $sub->name, 'category' => $sub->category?->name])
                    ->all(),
                'brands' => Brand::where('status', 1)->orderBy('name')->pluck('name')->all(),
                'product_types' => array_values(array_filter(array_keys(getAllType()))),
                'genders' => Product::GENDERS,
                // Categories where products are tagged with a gender
                'gender_categories' => Category::where('status', 1)
                    ->whereHas('products', fn ($query) => $query->whereNotNull('gender'))
                    ->orderBy('name')->pluck('name')->all(),
            ];
        });
    }

    // Suggest catalog names closest to the input
    public function suggest(string $input, array $names, int $limit = 3): array
    {
        $needle = $this->normalize($input);

        if ($needle === '') {
            return [];
        }

        return collect($names)
            ->map(function (string $name) use ($needle) {
                $hay = $this->normalize($name);

                $score = str_contains($hay, $needle) || str_contains($needle, $hay)
                    ? 1.0
                    : 1 - levenshtein($needle, $hay) / max(strlen($needle), strlen($hay));

                return ['name' => $name, 'score' => $score];
            })
            ->filter(fn ($row) => $row['score'] >= self::MIN_SCORE)
            ->sortByDesc('score')
            ->take($limit)
            ->pluck('name')
            ->values()
            ->all();
    }

    // Lowercase and singularize a name
    private function normalize(string $value): string
    {
        return Str::of($value)->lower()->trim()->singular()->value();
    }
}
