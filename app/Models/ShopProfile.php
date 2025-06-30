<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "slug",
        "banner",
        "phone",
        "email",
        "address",
        "description",
        "fb_link",
        "tw_link",
        "insta_link",
        "user_id"
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop_followers()
    {
        return $this->hasMany(ShopFollowers::class, 'shop_id');
    }

    // Number of products in the shop
    public function productsCount()
    {
        return $this->hasMany(Product::class)->count();
    }



    // Date Join 
    public function dateJoin()
    {
        return $this->created_at->format('d M Y');
    }

    // Number of followers 
    public function followersCount()
    {
        return $this->hasMany(ShopFollowers::class, 'shop_id')->count();
    }


    // Get Revenue By Month and Year
    public function getRevenueByMonthAndYear($month, $year)
    {
        $vendor_id = $this->id;
        return Order::whereHas('orderProducts', function ($query) use ($vendor_id) {
            $query->where('vendor_id', $vendor_id);
        })
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('total');
    }


    // Get Order Count By Month and Year
    public function getOrderCountByMonthAndYear($month, $year)
    {
        $vendor_id = $this->id;
        return Order::whereHas('orderProducts', function ($query) use ($vendor_id) {
            $query->where('vendor_id', $vendor_id);
        })
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();
    }


    // Get Number of Orders By Status
    public function getOrderCountByStatus($status)
    {
        $vendor_id = $this->id;
        return Order::whereHas('orderProducts', function ($query) use ($vendor_id) {
            $query->where('vendor_id', $vendor_id);
        })
            ->where('order_status', $status)
            ->count();
    }
}
