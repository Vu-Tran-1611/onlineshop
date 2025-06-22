<?php
// Set active for admin navbar

use App\Models\Chat;
use App\Models\ShopProfile;
use App\Models\User;
use App\Models\UserAddresses;

function setActive(array $routes)
{
    foreach ($routes as $route) {
        if (request()->routeIs($route))
            return "active";
    }
};

// Check table is Empty 
function isTableEmpty($model)
{
    return $model->isEmpty();
}

// Check sale 

function checkSale($product)
{
    $currentDate = Date("Y-m-d");
    if (
        $product->offer_price > 0 && $currentDate >= $product->offer_start_price
        && $currentDate <=  $product->offer_end_price
    ) return true;
    else return false;
}

// Calculate Sale

function calculateSalePercent($product)
{
    $discountPrice = $product->price - $product->offer_price;
    $percent =  ($discountPrice / $product->price) * 100;
    return number_format($percent, 0);
}

// Get Product type
function getProductType($product)
{
    $type = "";
    switch ($product->product_type) {
        case 'top':
            $type = "TOP";
            break;
        case 'best':
            $type = "BEST";
            break;
        case 'featured':
            $type = "FEATURED";
            break;
        case 'new_arrival':
            $type = "NEW";
            break;
        default:
            $type = null;
            break;
    }
    return $type;
}


function getAllType()
{
    return ["featured" => "Featured", "best" => "Best", "top" => "Top", "new" => "New Arrival"];
}

// Chat -------------------------------------
function getReceivers(): array
{
    $user = auth()->user();
    $userId = $user->id;

    // Get all unique user IDs that have chatted with the current user
    $receiverIds = Chat::where('sender_id', $userId)->pluck('receiver_id')->toArray();
    $senderIds = Chat::where('receiver_id', $userId)->pluck('sender_id')->toArray();
    $chatUserIds = array_unique(array_merge($receiverIds, $senderIds));
    $chatUserIds = array_diff($chatUserIds, [$userId]); // Remove self if present

    if ($user->role === 'user') {
        // Get shop profiles for each user ID
        return ShopProfile::whereIn('user_id', $chatUserIds)->get()->all();
    } else {
        // Get users for each user ID
        return User::whereIn('id', $chatUserIds)->get()->all();
    }
}


function getUserOrderAddress()
{
    $addr = session()->get('user_delivery_address');
    if ($addr) return $addr;
    // If address not set in session, get the default address
    $addr =  UserAddresses::where('user_id', auth()->user()->id)->where('is_default', 1)->first();
    return $addr->id;
}

// Chat -------------------------------------
