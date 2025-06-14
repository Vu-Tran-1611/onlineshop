<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\UploadTrait;

class UserReviewsController extends Controller
{
    use UploadTrait;

    // Create User Review For Product 
    public function create(Request $request)
    {
        $request->validate([
            "product_id" => "required|exists:products,id",
            "rating" => "required|integer|min:1|max:5",
            "review" => "nullable|string",
        ]);

        $user = auth()->user();

        // Check if user has already reviewed this product
        $existingReview = $user->userReviews()->where('product_id', $request->product_id)->first();
        if ($existingReview) {
            return response()->json([
                "message" => "You have already reviewed this product."
            ], 201);
        }
        $images = "";
        if ($request->hasFile("images")) {
            $request->validate([
                "images" => "required|array|max:5",
                "images.*" => "image|mimes:jpeg,png,jpg,gif,svg|max:2048"
            ]);

            $paths = $this->uploadMultiImage($request, "images", "uploads");
            $images = json_encode($paths);
        }
        $review = $user->userReviews()->create([
            "product_id" => $request->product_id,
            "rating" => $request->rating,
            "review" => $request->review,
            "images" => $images
        ]);

        return response()->json([
            "message" => "Review created successfully.",
            "data" => $review
        ], 200);
    }
}
