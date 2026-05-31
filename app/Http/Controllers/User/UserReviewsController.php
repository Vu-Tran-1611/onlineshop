<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\UploadTrait;
use App\Models\UserProductInteraction;
use App\Jobs\StoreProductInteractionJob;
class UserReviewsController extends Controller
{
    use UploadTrait;

    // Create User Review For Product
    public function create(Request $request)
    {
        $request->validate([
            "product_id" => "required|exists:products,id",
            "rating" => "nullable|integer|min:0|max:5",
            "review" => "nullable|string",
        ]);

        $reviewText = is_string($request->review) ? trim($request->review) : null;
        if (empty($reviewText)) {
            return response()->json([
                "message" => "Please provide a review comment."
            ], 422);
        }

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

            $images = $this->uploadMultiImage($request, "images", "uploads");
        }
        $review = $user->userReviews()->create([
            "product_id" => $request->product_id,
            "rating" => $request->rating,
            "review" => $reviewText,
            "images" => $images
        ]);

        if($request->rating !== null){
            StoreProductInteractionJob::dispatch($user->id, $request->product_id, "R" . $request->rating);
        }

        return response()->json([
            "message" => "Review submitted successfully.",
            "data" => $review
        ], 200);
    }
}
