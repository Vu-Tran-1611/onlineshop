<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ShopProfile;
use App\Models\Brand;
use App\Models\ProductImageGallery;
use App\Models\ProductVariant;
use App\Models\UserReviews;
use App\Models\OrderProduct;
use App\Models\UserProductInteraction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;

    // Build the document sent to the search index
    public function toSearchableArray(): array
    {
        $this->loadMissing(['brand:id,name', 'category:id,name', 'subCategory:id,name']);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'brand' => $this->brand?->name,
            'category' => $this->category?->name,
            'subcategory' => $this->subCategory?->name,
            'short_description' => $this->plainText($this->short_description),
            'long_description' => $this->plainText($this->long_description),
            'sku' => $this->sku,
            'category_id' => $this->category_id,
            'sub_category_id' => $this->sub_category_id,
            'brand_id' => $this->brand_id,
            // Sale dates as timestamps so the active price is checked at search time
            'price' => (float) $this->price,
            'offer_price' => (float) $this->offer_price,
            'offer_start' => $this->offer_start_price ? strtotime($this->offer_start_price . ' 00:00:00') : 0,
            'offer_end' => $this->offer_end_price ? strtotime($this->offer_end_price . ' 23:59:59') : 0,
            'product_type' => $this->product_type,
            'status' => (int) $this->status,
            'is_approved' => (int) $this->is_approved,
        ];
    }

    // Eager load relations when importing all products
    protected function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with(['brand:id,name', 'category:id,name', 'subCategory:id,name']);
    }

    // Strip HTML and keep words separated
    private function plainText(?string $html): string
    {
        return Str::squish(strip_tags(str_replace('<', ' <', (string) $html)));
    }

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

    public function userInteractions()
    {
        return $this->hasMany(UserProductInteraction::class);
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

    // Get all images (thumb_image + productImageGalleries)
    public function allImages()
{
    return Cache::remember("product_{$this->id}_all_images", 60 * 60, function () {
        $images = collect();

        if ($this->thumb_image) {
            $images->push((object) [
                'image' => $this->thumb_image,
                'name' => 'Thumbnail',
                'is_primary' => true
            ]);
        }

        $this->productImageGalleries->each(function ($gallery) use ($images) {
            $images->push((object) [
                'image' => $gallery->image,
                'name' => $gallery->name,
                'is_primary' => false
            ]);
        });

        return $images;
    });


}
}
