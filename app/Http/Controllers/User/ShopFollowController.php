<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShopFollowController extends Controller
{
    public function followUnfollow(Request $request)
    {

        $request->validate([
            'shop_id' => 'required|exists:shop_profiles,id',
        ]);

        $user = auth()->user();
        $shopId = $request->input('shop_id');

        // Check if the user is already following the shop
        $isFollowing = $user->shopFollows()->where('shop_id', $shopId)->exists();
        if ($isFollowing) {
            // Unfollow the shop
            $user->shopFollows()->where('shop_id', $shopId)->delete();
            return response()->json(['message' => 'Unfollowed successfully.', 'isFollowing' => false, 'success' => true], 200);
        } else {
            // Follow the shop
            $user->shopFollows()->create(['shop_id' => $shopId]);
            return response()->json(['message' => 'Followed successfully.', 'isFollowing' => true, 'success' => true], 201);
        }
    }
}
