<?php

namespace App\Services\Search;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductSearchIndex
{
    // Embedder name in config/scout.php
    private const EMBEDDER = 'products';

    // Hide results below this relevance (0-1) in hybrid search
    private const SCORE_THRESHOLD = 0.3;

    // Check if the Meilisearch index is configured
    public function enabled(): bool
    {
        return config('scout.driver') === 'meilisearch';
    }

    // Return IDs of active products matching the keywords and filters, most relevant first.
    // A semantic ratio (0-1) turns on hybrid search: 0 = keywords only, 1 = meaning only.
    public function ids(
        ?string $keywords,
        array $filters = [],
        array $sort = [],
        int $limit = 1000,
        ?float $semanticRatio = null
    ): array {
        $keywords = trim((string) $keywords);
        $filter = implode(' AND ', array_merge(['status = 1', 'is_approved = 1'], $filters));
        $options = ['filter' => $filter, 'limit' => $limit];
        if (!empty($sort)) {
            $options['sort'] = $sort;
        }

        // Hybrid search needs a query to embed
        if ($semanticRatio === null || $keywords === '') {
            return $this->search($keywords, $options);
        }

        try {
            return $this->search($keywords, $options + [
                'hybrid' => ['embedder' => self::EMBEDDER, 'semanticRatio' => $semanticRatio],
                // Keywords must all match; the semantic side covers partial meaning
                'matchingStrategy' => 'all',
                'rankingScoreThreshold' => self::SCORE_THRESHOLD,
            ]);
        } catch (Throwable $e) {
            // Fall back to keyword search when embedding fails (e.g. OpenAI is down)
            Log::warning('Hybrid search failed, using keyword search', ['error' => $e->getMessage()]);

            return $this->search($keywords, $options);
        }
    }

    // Run a raw Meilisearch search and return product IDs
    private function search(string $keywords, array $options): array
    {
        return Product::search($keywords, function ($meilisearch, $query) use ($options) {
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
