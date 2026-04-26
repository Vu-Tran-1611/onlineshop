<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserProductInteraction;
use Illuminate\Support\Facades\Cache;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wishlists = auth()->user()->wishlists()->with("product")->paginate(10);
        $categories = Cache::remember('categories', 60 * 60, function () {
            return \App\Models\Category::where("status", 1)->with('subCategories',function($query){
                $query->where("status", 1);
            })->get()->take(20);
        });

        return view("frontend.pages.profile-wishlist", [
            "wishlists" => $wishlists,
            "title" => "Wishlist",
            "categories" => $categories
        ]);
    }

    // Add to wishlist
    public function addToWishlist(Request $request)
    {

        $request->validate([
            "product_id" => "required|exists:products,id",
        ]);

        $user = auth()->user();
        $productId = $request->input("product_id");

        $wishlist = $user->wishlists()->firstOrCreate([
            "product_id" => $productId
        ]);

        // Store user interaction
        UserProductInteraction::create([
            "user_id" => $user->id,
            "product_id" => $productId,
            "interaction_type" => UserProductInteraction::WISHLIST_ADD
        ]);

        return response()->json(["message" => "Product added to your wishlist.", "success" => true], 201);
    }

    // Remove from wishlist
    public function removeFromWishlist(Request $request)
    {
        $user = auth()->user();
        $productId = $request->input("product_id");
        $wishlistItem = $user->wishlists()->where("product_id", $productId)->first();

        if (!$wishlistItem) {
            return response()->json(["message" => "Product not found in your wishlist.", "success" => false], 404);
        }

        $wishlistItem->delete();
        // Store user interaction
        UserProductInteraction::create([
                "user_id" => $user->id,
                "product_id" => $productId,
                "interaction_type" => UserProductInteraction::WISHLIST_REMOVE
        ]);
        return response()->json(["message" => "Product removed from your wishlist.", "success" => true], 200);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
