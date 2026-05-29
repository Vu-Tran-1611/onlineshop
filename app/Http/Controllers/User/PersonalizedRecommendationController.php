<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\UserProductInteraction;
use App\Services\RecommendationApiService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
class PersonalizedRecommendationController extends Controller
{
    // More Products based on Comirec
    public function moreProductsByComirec(Request $request)
    {
        $categories = Cache::remember('categories', 60 * 60, function () {
            return Category::where("status", 1)->with('subCategories')->get()->take(20);
        });
        $comirecRecommendedProducts = collect();
        $title = "Recommended for You";
        try {
            $interactions = UserProductInteraction::where("user_id", Auth::id())
                ->orderBy("created_at", "desc")
                ->whereNotIn("interaction_type",["wishlist_remove","cart_remove","R0"])
                ->get()
                ->map(function ($interaction) {
                    return [
                        "product_id" => $interaction->product_id,
                        "interaction_type" => $interaction->interaction_type
                    ];
                })
                ->toArray();
            // dd($interactions);
            $numberOfUniqueInteractedProducts = count(array_unique(array_column($interactions, 'product_id')));
            if(empty($interactions) || $numberOfUniqueInteractedProducts < 1){
                $comirecRecommendedProducts = collect();
            }else{
                // dd($interactions);
                $recommendationService = new RecommendationApiService();
                //------------- Comirec ----------------
                $comirecResponse = $recommendationService->getUserRecentRecommendations(Auth::id(),$interactions,"comirec", 40);
                $comirecRecommendedProductIDs = $comirecResponse["recommendations"];
                $comirecRecommendedProducts = Product::whereIn("id", $comirecRecommendedProductIDs)
                ->where("status", 1)
                ->where("is_approved", 1)
                ->orderByRaw("FIELD(id, " . implode(',', $comirecRecommendedProductIDs) . ")")
                ->paginate(20)
                ->withQueryString();
                //------------- Comirec ----------------


            }
        } catch (\Exception $e) {
            Log::error("Error fetching user recommendations: " . $e->getMessage());
            $comirecRecommendedProducts = collect();
        }
        return view("frontend.pages.more-products", [
            "filteredProducts" => $comirecRecommendedProducts,
            "type" => "comirec",
            "title" => $title,
            "categories" => $categories,
        ]);
    }
}
