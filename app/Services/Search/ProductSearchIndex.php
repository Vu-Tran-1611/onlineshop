<?php

namespace App\Services\Search;

use App\Models\Product;

class ProductSearchIndex
{
    // Check if the Meilisearch index is configured
    public function enabled(): bool
    {
        return config('scout.driver') === 'meilisearch';
    }

    // Return IDs of active products matching the keywords and filters, most relevant first
    public function ids(?string $keywords, array $filters = [], array $sort = [], int $limit = 1000): array
    {
        $filter = implode(' AND ', array_merge(['status = 1', 'is_approved = 1'], $filters));

        return Product::search(trim((string) $keywords), function ($meilisearch, $query, $options) use ($filter, $sort, $limit) {
            $options['filter'] = $filter;
            $options['limit'] = $limit;
            if (!empty($sort)) {
                $options['sort'] = $sort;
            }

            return $meilisearch->search($query, $options);
        })->keys()->map(fn ($id) => (int) $id)->all();
    }

    // Build a filter on the price customers pay today (sale price during a sale)
    public static function activePrice(string $operator, float $value): string
    {
        $now = time();
        $onSale = "offer_price > 0 AND offer_start <= {$now} AND offer_end >= {$now}";

        return "(({$onSale}) AND offer_price {$operator} {$value}) OR (NOT ({$onSale}) AND price {$operator} {$value})";
    }

    // Build a gender filter, e.g. men also matches unisex
    public static function gender(?string $gender): ?string
    {
        $values = \App\Models\Product::gendersFor($gender);

        return $values ? 'gender IN [' . implode(', ', array_map('json_encode', $values)) . ']' : null;
    }

    // Build an "IN" filter for numeric IDs
    public static function in(string $field, array $ids): string
    {
        return $field . ' IN [' . implode(', ', array_map('intval', $ids)) . ']';
    }
}
