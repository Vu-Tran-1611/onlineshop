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
    public function getInteractions($userId, $interactionTypes = [])
    {
        return UserProductInteraction::where("user_id", $userId)
            ->orderBy("created_at", "desc")
            ->when(!empty($interactionTypes), function ($query) use ($interactionTypes) {
                $query->whereIn("interaction_type", $interactionTypes);
            })
            ->get()
            ->map(function ($interaction) {
                return [
                    "product_id" => $interaction->product_id,
                    "category_id" => $interaction->product ? $interaction->product->sub_category_id : null,
                    "interaction_type" => $interaction->interaction_type
                ];
            })
            ->toArray();
    }

    // More Products based on Comirec
    public function moreProductsByComirec(Request $request)
    {
        $categories = Cache::remember('categories', 60 * 60, function () {
            return Category::where("status", 1)->with('subCategories')->get()->take(20);
        });
        $comirecRecommendedProducts = collect();
        $title = "Continue Exploring Your Style";
        try {
            $interactions = $this->getInteractions(Auth::id(), ["click", "wishlist_add"]);

            $numberOfUniqueInteractedProducts = count(array_unique(array_column($interactions, 'product_id')));
            if(empty($interactions) || $numberOfUniqueInteractedProducts < 1){
                $comirecRecommendedProducts = collect();
            }else{

                $recommendationService = new RecommendationApiService();
                //------------- Comirec ----------------
                $comirecResponse = $recommendationService->getUserRecentRecommendations(Auth::id(),$interactions,"comirec", 60);
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


    // More Products based on Two Tower Model
    public function moreProductsByTwoTower(Request $request)
    {
        $categories = Cache::remember('categories', 60 * 60, function () {
            return Category::where("status", 1)->with('subCategories')->get()->take(20);
        });
        $twoTowerRecommendedProducts = collect();
        $title = "Personalized Recommendations";
        try {
            $interactions = $this->getInteractions(Auth::id(), ["click", "wishlist_add", "cart_add", "R5", "R4", "R3"]);
            $numberOfUniqueInteractedProducts = count(array_unique(array_column($interactions, 'product_id')));
            if(empty($interactions) || $numberOfUniqueInteractedProducts < 1){
                $twoTowerRecommendedProducts = collect();
            }else{

                $recommendationService = new RecommendationApiService();
                //------------- Two Tower ----------------
                $twoTowerResponse = $recommendationService->getUserRecentRecommendations(Auth::id(),$interactions,"twotower", 60);
                $twoTowerRecommendedProductIDs = $twoTowerResponse["recommendations"];
                $twoTowerRecommendedProducts = Product::whereIn("id", $twoTowerRecommendedProductIDs)
                ->where("status", 1)
                ->where("is_approved", 1)
                ->orderByRaw("FIELD(id, " . implode(',', $twoTowerRecommendedProductIDs) . ")")
                ->paginate(20)
                ->withQueryString();
                //------------- Two Tower ----------------
            }
        } catch (\Exception $e) {
            Log::error("Error fetching user recommendations: " . $e->getMessage());
            $twoTowerRecommendedProducts = collect();
        }
        return view("frontend.pages.more-products", [
            "filteredProducts" => $twoTowerRecommendedProducts,
            "type" => "twotower",
            "title" => $title,
            "categories" => $categories,
        ]);
    }


    public function moreProductsByBert4Rec(Request $request)
    {

        $categories = Cache::remember('categories', 60 * 60, function () {
            return Category::where("status", 1)->with('subCategories')->get()->take(20);
        });
        $bert4RecRecommendedProducts = collect();
        $title = "Recommended Based On Your Cart";
        try {
            $interactions = $this->getInteractions(Auth::id(), ["cart_add", "cart_remove"]);


            $numberOfUniqueInteractedProducts = count(array_unique(array_column($interactions, 'product_id')));
            if(empty($interactions) || $numberOfUniqueInteractedProducts < 1){
                $bert4RecRecommendedProducts = collect();
            }else{

                $recommendationService = new RecommendationApiService();
                //------------- Bert4rec ----------------
                $bert4recResponse = $recommendationService->getUserRecentRecommendations(Auth::id(),$interactions,"bert4rec", 60);
                $bert4recRecommendedProductIDs = $bert4recResponse["recommendations"];
                $bert4RecRecommendedProducts = Product::whereIn("id", $bert4recRecommendedProductIDs)
                ->where("status", 1)
                ->where("is_approved", 1)
                ->orderByRaw("FIELD(id, " . implode(',', $bert4recRecommendedProductIDs) . ")")
                ->paginate(20)
                ->withQueryString();
                //------------- Bert4rec ----------------
            }
        } catch (\Exception $e) {
            Log::error("Error fetching user recommendations: " . $e->getMessage());
            $bert4RecRecommendedProducts = collect();
        }
        return view("frontend.pages.more-products", [
            "filteredProducts" => $bert4RecRecommendedProducts,
            "type" => "bert4rec",
            "title" => $title,
            "categories" => $categories,
        ]);
    }
}
