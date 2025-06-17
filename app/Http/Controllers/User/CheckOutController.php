<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Models\ShopProfile;
use App\Models\UserAddresses;
use Illuminate\Support\Facades\Auth;

class CheckOutController extends Controller
{
    public function index()
    {
        $title = "Checkout";
        Cart::session("checked");
        $userID = Auth::user()->id;
        $vendors = array();
        foreach (Cart::getContent() as $item) {
            $vendors[] = ShopProfile::findOrFail($item->attributes['vendor_id'])->toArray();
        }
        $vendorsCollection = collect($vendors);
        $uniqueVendorsCollection = $vendorsCollection->unique("id");
        $uniqueVendorsArray =   $uniqueVendorsCollection->toArray();
        $address = UserAddresses::where([
            ["user_id", Auth::user()->id],
            ["is_default", true],
        ])->first();
        $addresses = UserAddresses::where('user_id', Auth::user()->id)->get();
        return view("frontend.pages.check-out", [
            'vendors' => $uniqueVendorsArray,
            'totalQuantity' => Cart::getTotalQuantity(),
            'title' => $title,
            'address' => $address,
            'addresses' =>  $addresses,
        ]);
    }


    // Set user address for this order
    public function setUserDeliveryAddress(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:user_addresses,id',
        ]);
        $address = UserAddresses::findOrFail($request->address_id);
        Cart::session("checked");
        // Store the address ID in the session for later use    
        session()->put('user_delivery_address', $address->id);
        return response([
            "address" => $address,
            "status" => "success",
        ]);
    }
}
