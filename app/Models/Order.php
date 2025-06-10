<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public $fillable = [
        "invoice_id",
        "user_id",
        "sub_total",
        "total",
        "product_qty",
        "payment_method",
        "payment_status",
        "user_address_id",
        "coupon",
        "order_status",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
}
