<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\User\AddressController;
use App\Http\Controllers\User\CheckOutController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\UserMessageController;




// Profile -------------------------------------------------
Route::prefix("profile")->name('profile.')->group(function () {
    Route::post("update-password", [ProfileController::class, "updatePassword"])->name("update-password");
    Route::post("update-profile", [ProfileController::class, "updateProfile"])->name("update-profile");
    Route::get("account", [ProfileController::class, "index"])->name("account");
    Route::get("address", [AddressController::class, "index"])->name("address");
    Route::get("orders", [OrderController::class, "index"])->name("orders");
    Route::get("orders/{id}", [OrderController::class, "show"])->name("orders.show");
    Route::get("favorite", [OrderController::class, "index"])->name("favorite");
});

// Profile -------------------------------------------------

// Cart   -------------------------------------------------

Route::delete("/{id}/cart", [CartController::class, "delete"])->name("cart.delete");
Route::put("/cart", [CartController::class, "update"])->name("cart.update");
Route::get("/cart/get", [CartController::class, "get"])->name("cart.get");
Route::get("/cart", [CartController::class, "index"])->name("cart");
// Add to cart 
Route::post("/add-to-cart", [CartController::class, "addToCart"])->name("add-to-cart");
// apply coupon 
Route::put("/apply-coupon", [CartController::class, "applyCoupon"])->name("apply-coupon");

// Cart   ------------------------------------------------- 



// Payment   ------------------------------------------------- 
Route::post("payment/make-payment", [PaymentController::class, "makePayment"])->name('payment.make-payment');
Route::get("payment/payment-success", [PaymentController::class, "paymentSuccess"])->name("payment.payment-success");
Route::get("payment/payment-cancel", [PaymentController::class, "paymentCancel"])->name("payment.payment-cancel");
// Payment   ------------------------------------------------- 




// Check out   ------------------------------------------------- 

Route::get("/check-out", [CheckOutController::class, "index"])->name("check-out");

// Check out   ------------------------------------------------- 




// Address ------------------------------------------------- 
Route::put("/address/{id}/set-default", [AddressController::class, "setDefault"])->name("address.set-default");
Route::get("/address/get", [AddressController::class, "get"])->name("address.get");
Route::resource("address", AddressController::class);

// Address ------------------------------------------------- 



// Chat ------------------------------------------------- 
Route::post("message/send-message", [UserMessageController::class, 'sendMessage'])->name("message.send-message");
Route::get('message/get-message', [UserMessageController::class, "getMessage"])->name("message.get-message");
// Chat ------------------------------------------------- 
