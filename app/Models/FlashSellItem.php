<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSellItem extends Model
{
    use HasFactory;
    protected $fillable = [
        "show_at_home",
        "product_id",
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
