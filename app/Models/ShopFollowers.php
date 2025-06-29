<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopFollowers extends Model
{
    use HasFactory;
    protected $fillable = [
        'shop_id',
        'user_id',
    ];
    public function shop()
    {
        return $this->belongsTo(ShopProfile::class, 'shop_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
