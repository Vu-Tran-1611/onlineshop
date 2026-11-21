<?php

namespace App\Http\Resources\Ai;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Shape of one product in the availability API response.
 * Change the output format here without touching the controller or service.
 *
 * @mixin \App\Models\Product
 */
class ProductAvailabilityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isOnSale = checkSale($this->resource);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'qty' => (int) $this->qty,
            'in_stock' => $this->qty > 0,
            'price' => (float) $this->price,
            'offer_price' => $this->offer_price !== null ? (float) $this->offer_price : null,
            'is_on_sale' => $isOnSale,
            'current_price' => (float) ($isOnSale ? $this->offer_price : $this->price),
            'url' => url('/product/' . $this->slug),
        ];
    }
}
