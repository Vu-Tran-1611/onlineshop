<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Chat;
use App\Models\FlashSellItem;
use App\Models\Product;
use App\Models\ShopProfile;
use App\Models\Slider;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Cart;

class HomeController extends Controller
{
    public function home()
    {

        $sliders = Slider::get()->sortBy("serial");
        $categoryBanners = Category::get()->take(6);
        $categories = Category::with("subCategories")->get();
        $hotCategories = Category::get()->take(6);
        $brands = Brand::get()->take(10);

        $featuredProducts = Product::where("product_type", "featured")
            ->where("status", 1)
            ->where("is_approved", 1)
            ->get()->take(12);
        $topProducts = Product::where("product_type", "top")
            ->where("status", 1)
            ->where("is_approved", 1)
            ->get()->take(12);
        $newProducts = Product::where("product_type", "new_arrival")
            ->where("status", 1)
            ->where("is_approved", 1)
            ->get()->take(12);
        $bestProducts = Product::where("product_type", "best")
            ->where("status", 1)
            ->where("is_approved", 1)
            ->get()->take(12);

        $flashSellProducts = FlashSellItem::with("product")->get();
        return view(
            "frontend.pages.home",
            compact(
                "sliders",
                "categoryBanners",
                "categories",
                "hotCategories",
                "brands",
                "topProducts",
                "newProducts",
                "flashSellProducts",
                "featuredProducts",
                "bestProducts"
            )
        );
    }
    // return product page 
    // {product?}/{type?}/{subcategory?}/{category?}/{brand?}/{vendor?}
    public function product(Request $request)
    {
        $allCategories = Category::with("subCategories")->get();
        // Product Detail 
        $product = Product::where("slug", $request->product)->first();
        if ($product) {
            $shop = ShopProfile::findOrFail($product->shop_profile_id);

            $productsBelongsToShop =  Product::where("shop_profile_id", $shop->id)
                ->where("status", 1)
                ->where("is_approved", 1)->get()->take(6);

            $productsBelongsToSameCategory =  Product::where("category_id", $product->category_id)
                ->where("status", 1)
                ->where("is_approved", 1)->get()->take(30);

            $reviewsQuery = $product->userReviews()->with("user");
            $userReview = null;
            if (Auth::check()) {
                $userId = Auth::id();
                $userReview = $reviewsQuery->where('user_id', $userId)->first();
                $reviewsQuery = $product->userReviews()
                    ->with('user')
                    ->where('user_id', '!=', $userId);
            }

            // Paginate các review còn lại
            $otherReviews = $reviewsQuery->paginate(1);
            // $averageRating = $reviews->avg("rating");
            // Get Chat -----------------------------
            // dd($otherReviews->userReview);
            return view("frontend.pages.product", [
                "categories" => $allCategories,
                "product" => $product,
                "shop" => $shop,
                "productsBelongsToShop" => $productsBelongsToShop,
                "productsBelongsToSameCategory" => $productsBelongsToSameCategory,
                "userReview" => $userReview,
                "otherReviews" => $otherReviews,
            ]);
        }
        // Product based on category filter
        else {
            $subCategory = null;
            $activeSub = null;
            $brandSlugs = null;
            $brandSlugsID = null;
            $priceRange = null;
            // if Price Order was chosen 
            $order = $request->order ?  $request->order : "asc";
            // If Price was chosen 
            if ($request->price_range) {
                $priceRange = explode(",", $request->price_range);
            }
            // if brand filter was chosen 

            if ($request->brand_slug) {
                $brandSlugs = explode(",", $request->brand_slug);
                foreach ($brandSlugs as $key => $value) {
                    $brandSlugsID[] = Brand::where("slug", $value)->pluck("id")->toArray();
                }
            }
            // If type was chosen 
            if ($request->type) $type = $request->type;
            else $type = "featured";

            // If subcategory was chosen 
            if ($request->subcategory) {
                $subCategory = SubCategory::where("slug", $request->subcategory)->first();
                $activeSub = $subCategory->slug;
            };
            $category = Category::where("slug", $request->category)->first();
            // fetch brands based on category
            $brands = Brand::with("categories")->whereHas("categories", function ($query) use ($category) {
                $query->where("categories.id", $category->id);
            })->get();

            $products = Product::where([
                ["category_id", $category->id],
                ["product_type", $type]
            ])
                ->where(function ($query) use ($subCategory) {
                    if ($subCategory) $query->where("sub_category_id", $subCategory->id);
                })
                ->where(function ($query) use ($brandSlugsID) {
                    if ($brandSlugsID) $query->whereIn('brand_id', array_merge(...$brandSlugsID));
                })
                ->where(function ($query) use ($priceRange) {
                    if ($priceRange) {
                        if (isset($priceRange[0]) && isset($priceRange[1]))
                            $query->whereBetween('price', $priceRange);
                        else if (isset($priceRange[0]))
                            $query->where('price', ">=", $priceRange);
                        else
                            $query->where('price', "<=", $priceRange);
                    };
                })
                ->where("is_approved", 1)
                ->where("status", 1)
                ->orderBy("price", $order)
                ->paginate(3);
            return view("frontend.pages.category", [
                "categories" => $allCategories,
                "category" => $category,
                "products" => $products,
                "activeType" => $type,
                "activeSub" =>  $activeSub,
                "slug" => $category->slug,
                "brands" => $brands,
            ]);
        }
    }

    public function notFound()
    {
        $title = "Not FOund";
        $user = Auth::user();
        $categories = Category::get();
        return view("frontend.pages.not-found", compact("title", "user", "categories"));
    }
}
