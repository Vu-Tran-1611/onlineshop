<?php

namespace App\Services\Ai;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;


class ProductAvailabilityService
{
    public const MATCH_EXACT = 'exact';
    public const MATCH_PARTIAL = 'partial';
    public const MATCH_NONE = 'none';


    public function findByName(string $name, int $limit = 5): array
    {
        $name = trim($name);

        $exact = $this->baseQuery()
            ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
            ->limit($limit)
            ->get();

        if ($exact->isNotEmpty()) {
            return ['match_type' => self::MATCH_EXACT, 'products' => $exact];
        }

        $partial = $this->baseQuery()
            ->where('name', 'like', '%' . $this->escapeLike($name) . '%')
            ->orderByRaw('CHAR_LENGTH(name)')
            ->limit($limit)
            ->get();

        return [
            'match_type' => $partial->isNotEmpty() ? self::MATCH_PARTIAL : self::MATCH_NONE,
            'products' => $partial,
        ];
    }


    private function baseQuery(): Builder
    {
        return Product::query()
            ->select([
                'id',
                'name',
                'slug',
                'qty',
                'price',
                'offer_price',
                'offer_start_price',
                'offer_end_price',
            ])
            ->where('status', 1)
            ->where('is_approved', 1);
    }

    private function escapeLike(string $value): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
    }
}
