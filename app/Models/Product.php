<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "slug",
        "thumb_image",
        "shop_profile_id",
        "category_id",
        "sub_category_id",
        "brand_id",
        "qty",
        "short_description",
        "long_description",
        "sku",
        "price",
        "offer_price",
        "offer_start_price",
        "offer_end_price",
        "status",
        "is_approved",
        "seo_title",
        "seo_description",
        "product_type",
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
    public function shopProfile()
    {
        return $this->belongsTo(ShopProfile::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function productImageGalleries()
    {
        return $this->hasMany(ProductImageGallery::class);
    }
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function userReviews()
    {
        return $this->hasMany(UserReviews::class);
    }
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    // Average Rating
    public function averageRating()
    {
        return Cache::remember("product_{$this->id}_average_rating", 60 * 60, function () {
            return $this->userReviews()->avg('rating');
        });
    }
    // Number of Reviews
    public function numberOfReviews()
    {
        return Cache::remember("product_{$this->id}_number_of_reviews", 60 * 60, function () {
            return $this->userReviews()->count();
        });
    }
    // Sold Count
    public function soldCount()
    {
        return Cache::remember("product_{$this->id}_sold_count", 60 * 60, function () {
            return $this->orderProducts()->where('status', 'delivered')->sum('qty');
        });
    }
}
