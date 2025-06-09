<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\ShopProfile;
use Carbon\Carbon;

use function PHPUnit\Framework\isEmpty;

class CartController extends Controller
{
    // return cart view 
    public function index()
    {
        $title = "Cart";
        Cart::session("checked")->clearCartConditions();
        Cart::session("checked")->clear();
        $userID = Auth::user()->id;
        Cart::session($userID);
        $vendors = array();
        foreach (Cart::getContent() as $item) {
            $vendors[] = ShopProfile::findOrFail($item->attributes['vendor_id'])->toArray();
        }
        $vendorsCollection = collect($vendors);
        $uniqueVendorsCollection = $vendorsCollection->unique("id");
        $uniqueVendorsArray =   $uniqueVendorsCollection->toArray();
        return view("frontend.pages.cart", [
            'vendors' => $uniqueVendorsArray,
            'totalQuantity' => Cart::getTotalQuantity(),
            'title' =>  $title,
        ]);
    }


    // Add to cart 
    public function addToCart(Request $request)
    {
        Cart::session(Auth::user()->id);

        $item = $request->except('temp_id', 'attributes');
        $isShowInMiniCart = Cart::get($request->id) ? false : true;
        Cart::add($item);
        if ($request->input("attributes")) {
            $variants = array_merge(...json_decode($request->input("attributes"), true));

            Cart::update($item['id'], ["attributes" =>  $variants]);
        }
        return response([
            "status" => 'success',
            "message" => "Product was added to your cart",
            "cart" => Cart::getTotalQuantity(),
            "price" =>  $request->price,
            "variants" => $variants,
            "name" => $request->name,
            "isShowInMiniCart" => $isShowInMiniCart,
        ]);
    }

    // Update Cart  
    public function update(Request $request)
    {
        Cart::session(Auth::user()->id);
        Cart::update($request->id, ['quantity' =>  array(
            'relative' => false,
            'value' => intval($request->quantity),
        )]);
        return response(['status' => "success"]);
    }
    public function get(Request $request)
    {
        Cart::session("checked")->clear();
        Cart::session(Auth::user()->id);
        if ($request->type == "all") {
            if ($request->isCheck == 'true') {
                $quantity = Cart::getTotalQuantity();
                $totalPrice = Cart::getTotal();
                foreach (Cart::getContent() as $item) {
                    Cart::session("checked")->add($item->toArray());
                }
                return response([
                    'total' => Cart::session("checked")->getTotal(),
                    "quantity" => Cart::session("checked")->getTotalQuantity(),
                ]);
            } else {
                return response([
                    "total" => 0,
                    "quantity" => 0,
                ]);
            }
        } else {
            $idArray = json_decode($request->ids);
            foreach ($idArray as $id) {
                $item = Cart::session(Auth::user()->id)->get($id)->toArray();
                Cart::session("checked")->add($item);
            }
            return response([
                'total' => Cart::session("checked")->getTotal(),
                "quantity" => Cart::session("checked")->getTotalQuantity(),
            ]);
        }
    }
    // Delete 
    public function delete(string $id)
    {
        Cart::session(Auth::user()->id);
        Cart::remove($id);
        return response(["status" => "success", "total" => Cart::getTotalQuantity()]);
    }

    // Check coupon 
    public function applyCoupon(Request $request)
    {
        Cart::session("checked");
        // Check is Cart session checked empty 
        if (count(Cart::getContent()) <= 0) {
            return response([
                "status" => "unsuccess",
                "title" => "Please Select The Item",
            ]);
        }
        $coupon = Coupon::where("code", $request->code)->first();

        if ($coupon) {
            // Check is coupon active 
            if (!$coupon->status) {
                return response([
                    "status" => "unsuccess",
                    "title" => "Sorry :(( Your Coupon Is Not Available",
                    "text" => "Let's Join Our Events To Get Coupon"
                ]);
            }
            // Check is coupon expired 
            $currentTime = Carbon::now();
            if (!$currentTime->between($coupon->start_date, $coupon->end_date)) {
                return response([
                    "status" => "unsuccess",
                    "title" => "Sorry :(( Your Coupon Is Expired",
                    "text" => "Let's Join Our Events To Get Coupon"
                ]);
            };
            // Check is coupon applied  
            $cartConditions = Cart::getConditions();
            foreach ($cartConditions as $condition) {
                if ($condition->getName() == $coupon->name) {
                    return response([
                        "status" => "unsuccess",
                        "title" => "Sorry :(( Your Coupon was applied",
                    ]);
                }
            }

            // Filter coupon
            if ($coupon->discount == "percentage") $value = '-' . strval($coupon->discount) . '%';
            else $value = '-' . strval($coupon->discount);
            $condition = new \Darryldecode\Cart\CartCondition(array(
                'name' => $coupon->name,
                'type' => 'coupon',
                'target' => 'total',
                'value' => $value,
            ));
            Cart::condition($condition);
            return response([
                "status" => "success",
                "title" => "Applied Coupon",
                "total" => Cart::getTotal(),
                "subtotal" => Cart::getSubTotal(),
            ]);
        } else return response([
            "status" => "unsuccess",
            "title" => "Sorry :(( Your Coupon Is Invalid",
            "text" => "Let's Join Our Events To Get Coupon"
        ]);
    }
}
