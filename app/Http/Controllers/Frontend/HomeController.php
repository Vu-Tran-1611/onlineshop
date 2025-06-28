<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Chat;
use App\Models\FlashSell;
use App\Models\FlashSellItem;
use App\Models\Product;
use App\Models\ShopProfile;
use App\Models\Slider;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Cart;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function home()
    {

        $sliders = Cache::remember('sliders', 60 * 60, function () {
            return Slider::where("status", 1)->get();
        });
        $categoryBanners = Cache::remember('category_banners', 60 * 60, function () {
            return Category::where("banner", "!=", null)
                ->where("status", 1)
                ->get()->take(6);
        });
        $categories = Cache::remember('categories', 60 * 60, function () {
            return Category::where("status", 1)->with('subCategories')->get()->take(20);
        });

        $hotCategories = Cache::remember('hot_categories', 60 * 60, function () {
            return Category::get()->take(6);
        });
        $brands = Cache::remember('brands', 60 * 60, function () {
            return Brand::where("status", 1)->get()->take(20);
        });
        $featuredProducts = Cache::remember('featured_products', 60 * 60, function () {
            return Product::where("product_type", "featured")
                ->where("status", 1)
                ->where("is_approved", 1)
                ->get()->take(20);
        });
        $topProducts = Cache::remember('top_products', 60 * 60, function () {
            return Product::where("product_type", "top")
                ->where("status", 1)
                ->where("is_approved", 1)
                ->get()->take(20);
        });
        $newProducts = Cache::remember('new_products', 60 * 60, function () {
            return Product::where("product_type", "new_arrival")
                ->where("status", 1)
                ->where("is_approved", 1)
                ->get()->take(20);
        });
        $bestProducts = Cache::remember('best_products', 60 * 60, function () {
            return Product::where("product_type", "best")
                ->where("status", 1)
                ->where("is_approved", 1)
                ->get()->take(20);
        });


        $flashSellProducts = FlashSellItem::with("product")->get();
        $flashSellEndDate = FlashSell::first();



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
                "bestProducts",
                "flashSellEndDate",
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
            $numberOfReviews = $reviewsQuery->count();
            $userReview = null;
            if (Auth::check()) {
                $userId = Auth::id();
                $userReview = $reviewsQuery->where('user_id', $userId)->first();
                $reviewsQuery = $product->userReviews()
                    ->with('user')
                    ->where('user_id', '!=', $userId);
            }


            $otherReviews = $reviewsQuery->paginate(10);
            $averageRating = round($product->userReviews()->avg('rating'), 1);

            return view("frontend.pages.product", [
                "categories" => $allCategories,
                "product" => $product,
                "shop" => $shop,
                "productsBelongsToShop" => $productsBelongsToShop,
                "productsBelongsToSameCategory" => $productsBelongsToSameCategory,
                "userReview" => $userReview,
                "otherReviews" => $otherReviews,
                "numberOfReviews" => $numberOfReviews,
                "averageRating" => $averageRating,
            ]);
        } else {
            // Product based on category filter

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
            $type = "";
            if ($request->type) $type = $request->type;

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
                ->when($type, function ($query) use ($type) {
                    $query->where("product_type", $type);
                })
                ->where("is_approved", 1)
                ->where("status", 1)
                ->orderBy("price", $order)
                ->paginate(10);
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


    // Products by search
    // Search products based on keyword, categories, subcategories, brands,

    public function productBySearch(Request $request)
    {
        $allCategories = Category::with("subCategories")->get();
        $keyword = $request->keyword;
        $categorySlug = $request->category ? $request->category : "";
        $from = $request->from ? $request->from : 0;
        $to = $request->to ? $request->to : 10000000;
        $order = $request->order ? $request->order : "asc";
        $priceRange = [$from, $to];
        $brandsSlugsArray =  $request->brand;
        $products = Product::query()
            ->where(function ($query) use ($keyword) {
                $query->where("name", "LIKE", "%$keyword%")
                    ->orWhere("short_description", "LIKE", "%$keyword%")
                    ->orWhere("long_description", "LIKE", "%$keyword%");
            })
            ->whereBetween("price", $priceRange)
            ->where("is_approved", 1)
            ->where("status", 1)
            ->where(function ($query) use ($categorySlug) {
                $query->whereHas("category", function ($q) use ($categorySlug) {
                    $q->where("slug", "LIKE", "%$categorySlug%");
                })
                    ->orWhereHas("subCategory", function ($q) use ($categorySlug) {
                        $q->where("slug", "LIKE", "%$categorySlug%");
                    });
            })
            ->when(!empty($brandsSlugsArray), function ($query) use ($brandsSlugsArray) {
                $query->whereHas("brand", function ($q) use ($brandsSlugsArray) {
                    $q->whereIn("slug", $brandsSlugsArray);
                });
            })
            ->with(["category", "brand"])
            ->orderBy("price", $order)
            ->paginate(12);


        // Get categories and brands that have products matching the search keyword
        $relatedCategories = Category::whereHas("products", function ($query) use ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where("name", "LIKE", "%$keyword%")
                    ->orWhere("short_description", "LIKE", "%$keyword%")
                    ->orWhere("long_description", "LIKE", "%$keyword%");
            })
                ->where("status", 1)
                ->where("is_approved", 1);
        })->with("subCategories")->get();

        $brands = Brand::whereHas("products", function ($query) use ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where("name", "LIKE", "%$keyword%")
                    ->orWhere("short_description", "LIKE", "%$keyword%")
                    ->orWhere("long_description", "LIKE", "%$keyword%");
            })
                ->where("status", 1)
                ->where("is_approved", 1);
        })->get();

        return view("frontend.pages.product-by-search", [
            "categories" => $allCategories,
            "relatedCategories" => $relatedCategories,
            "products" => $products,
            "keyword" => $keyword,
            "brands" => $brands,
        ]);
    }


    // More Products based on type
    public function moreProductsByType(Request $request)
    {

        $categories = Cache::remember('categories', 60 * 60, function () {
            return Category::where("status", 1)->with('subCategories')->get()->take(20);
        });

        $type = $request->type ? $request->type : "featured";
        if ($type == "top") $title = "Top Products";
        else if ($type == "new_arrival") $title = "New Arrival Products";
        else if ($type == "best") $title = "Best Products";
        else if ($type == "featured") $title = "Featured Products";
        else $title = "More Products";
        $filteredProducts = Product::where("product_type", $type)
            ->where("status", 1)
            ->where("is_approved", 1)
            ->with("category", "subCategory", "brand")
            ->orderBy("created_at", "desc")
            ->paginate(40);

        return view("frontend.pages.more-products", [
            "filteredProducts" => $filteredProducts,
            "type" => $type,
            "title" => $title,
            "categories" => $categories,
        ]);
    }


    // More Products based on brand
    public function moreProductsByBrand(Request $request)
    {
        $categories = Cache::remember('categories', 60 * 60, function () {
            return Category::where("status", 1)->with('subCategories')->get()->take(20);
        });

        $brandSlug = $request->brand;
        $brand = Brand::where("slug", $brandSlug)->first();
        if (!$brand) {
            return redirect()->route("not-found");
        }
        $title = "More Products by " . $brand->name;

        $filteredProducts = Product::where("brand_id", $brand->id)
            ->where("status", 1)
            ->where("is_approved", 1)
            ->with("category", "subCategory")
            ->orderBy("created_at", "desc")
            ->paginate(40);

        return view("frontend.pages.more-products", [
            "filteredProducts" => $filteredProducts,
            "type" => "brand",
            "title" => $title,
            "categories" => $categories,
        ]);
    }


    // More Products based on flash sale
    public function moreProductsByFlashSale(Request $request)
    {
        $categories = Cache::remember('categories', 60 * 60, function () {
            return Category::where("status", 1)->with('subCategories')->get()->take(20);
        });

        $title = "More Products by Flash Sale";
        $filteredProducts = Cache::remember('flash_sale_products', 60 * 60, function () {
            return FlashSellItem::with("product")
                ->whereHas("product", function ($query) {
                    $query->where("status", 1)
                        ->where("is_approved", 1);
                })
                ->paginate(40);
        });
        return view("frontend.pages.more-products", [
            "filteredProducts" => $filteredProducts,
            "type" => "flash_sale",
            "title" => $title,
            "categories" => $categories,
            "isFlashSell" => true
        ]);
    }

    // View Shop Page
    public function shop(Request $request)
    {
        $activeSub = null;

        $categories = Cache::remember('categories', 60 * 60, function () {
            return Category::where("status", 1)->with('subCategories')->get()->take(20);
        });
        $title = "Shop";
        $shop = ShopProfile::where("slug", $request->shop)->first();
        if (!$shop) {
            return redirect()->route("not-found");
        }

        // If type was chosen 
        $type = "";
        if ($request->type) $type = $request->type;
        // If subcategory was chosen
        $subCategory = null;
        if ($request->subcategory) {
            $subCategory = SubCategory::where("slug", $request->subcategory)->first();
            $activeSub = $subCategory->slug;
            if (!$subCategory) {
                return redirect()->route("not-found");
            }
        }

        // Products from shop
        $shopProducts =  Product::where("shop_profile_id", $shop->id)
            ->where("status", 1)
            ->where("is_approved", 1)
            ->when($type, function ($query) use ($type) {
                $query->where("product_type", $type);
            })
            ->when($subCategory, function ($query) use ($subCategory) {
                $query->where("sub_category_id", $subCategory->id);
            })
            ->with("category", "subCategory", "brand")
            ->orderBy("created_at", "desc")
            ->paginate(20);

        // Get distinct sub categories from shop products
        $shopCategories = Cache::remember("shop_categories_" . $shop->id, 60 * 60, function () use ($shop) {
            return Product::where("shop_profile_id", $shop->id)
                ->where("status", 1)
                ->where("is_approved", 1)
                ->with("category", "subCategory")
                ->get()
                ->pluck('subCategory')
                ->unique('id');
        });


        return view("frontend.pages.shop", [
            "categories" => $categories,
            "shopCategories" => $shopCategories,
            "type" => $type,
            "shopSlug" => $shop->slug,
            "shop" => $shop,
            "shopProducts" => $shopProducts,
            "activeType" => $type,
            "title" => $title,
            "activeSub" => $activeSub,
        ]);
    }


    // 404 page
    public function notFound()
    {
        $title = "Not Found";
        $categories = Cache::remember('categories', 60 * 60, function () {
            return Category::where("status", 1)->with('subCategories')->get()->take(20);
        });
        $user = Auth::user();
        return view("frontend.pages.not-found", compact("title", "user", "categories"));
    }
}
