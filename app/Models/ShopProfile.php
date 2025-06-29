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
        $vendor_id = $this->user_id;
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
        $vendor_id = $this->user_id;
        return Order::whereHas('orderProducts', function ($query) use ($vendor_id) {
            $query->where('vendor_id', $vendor_id);
        })
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();
    }

    // Get Top Ten Selling Products
    public function getTopTenSellingProducts()
    {
        $vendor_id = $this->user_id;
        return Order::whereHas('orderProducts', function ($query) use ($vendor_id) {
            $query->where('vendor_id', $vendor_id);
        })
            ->with(['orderProducts' => function ($query) use ($vendor_id) {
                $query->select('product_id', 'qty')
                    ->where('vendor_id', $vendor_id)
                    ->groupBy('product_id')
                    ->orderByRaw('SUM(qty) DESC')
                    ->limit(10);
            }])
            ->get()
            ->pluck('orderProducts')
            ->flatten()
            ->groupBy('product_id')
            ->map(function ($items) {
                return [
                    'product_id' => $items->first()->product_id,
                    'total_qty' => $items->sum('qty'),
                ];
            })
            ->sortByDesc('total_qty')
            ->take(10)
            ->values();
    }
}
