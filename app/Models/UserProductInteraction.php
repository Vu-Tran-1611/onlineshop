<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProductInteraction extends Model
{
    const CLICK = 'click';
    const WISHLIST_ADD = 'wishlist_add';
    const WISHLIST_REMOVE = 'wishlist_remove';
    const CART_ADD = 'cart_add';
    const CART_REMOVE = 'cart_remove';

    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_id',
        'interaction_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function types(): array
    {
        return [
            self::CLICK,
            self::WISHLIST_ADD,
            self::WISHLIST_REMOVE,
            self::CART_ADD,
            self::CART_REMOVE,
        ];
    }
}
