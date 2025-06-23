<?php

use App\Models\Category;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Frontend\ChatController;
use App\Http\Controllers\Vendor\ProductController;
use App\Http\Controllers\Vendor\ProductImageGalleryController;
use App\Http\Controllers\Vendor\ProductVariantController;
use App\Http\Controllers\Vendor\ProductVariantItemController;
use App\Http\Controllers\Vendor\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vendor\ShopProfileController;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\ProductImageGallery;
use App\Http\Controllers\User\UserMessageController;
use App\Http\Controllers\Vendor\OrderController;

// Profile -------------------------------------------------
Route::post("profile-update", [ProfileController::class, "profileUpdate"])->name("profile-update");

Route::post("password-update", [ProfileController::class, "passwordUpdate"])->name("password-update");

Route::get("profile", [ProfileController::class, "index"])->name("profile");
// Profile -------------------------------------------------


// Shop Profile -------------------------------------------------

Route::resource("shop-profile", ShopProfileController::class);

// Shop Profile -------------------------------------------------

// Product -------------------------------------------------
// Get sub category
Route::post("category/get-sub-categories", function (Request $request) {
    $categoryID = $request->categoryID;
    $subCategories = Category::findOrFail($categoryID)->subCategories;
    return response(["subCategories" => $subCategories]);
})->name("category.get-sub-categories");
// Get brand
Route::post("brand/get-brand", function (Request $request) {
    $categoryID = $request->categoryID;
    $brands = Brand::whereHas("categories", function ($query) use ($categoryID) {
        $query->where("categories.id",  $categoryID);
    })->get();
    return response(["brands" => $brands]);
})->name("brand.get-brand");
Route::put("product/{id}/change-status", [ProductController::class, "changeStatus"])->name("product.change_status");
Route::put("product/change-type", [ProductController::class, "changeType"])->name("product.change_type");
Route::resource("product", ProductController::class);
// Product -------------------------------------------------


// Product Gallery  -------------------------------------------------
Route::put("product/{id}/image-gallery", [ProductImageGalleryController::class, "updateName"])->name("product.image-gallery");
Route::resource("product.image-gallery", ProductImageGalleryController::class);
// Product Gallery  -------------------------------------------------


// Product Variants -------------------------------------------------
Route::put("product/variant/{id}/change-is-swipe", [ProductVariantController::class, 'changeIsSwipe'])->name("product.variant.change_is_swipe");
Route::put("product/variant/{id}/change-status", [ProductVariantController::class, 'changeStatus'])->name("product.variant.change_status");
Route::resource("product.variant", ProductVariantController::class);
// Product Variants -------------------------------------------------

// Product Variant Items -------------------------------------------------
Route::put("product/variant/item/{id}/is-default", [ProductVariantItemController::class, "isDefault"])->name("product.variant.item.is_default");
Route::put("product/variant/item/{id}/change-status", [ProductVariantItemController::class, "changeStatus"])->name("product.variant.item.change_status");
Route::resource("product.variant.item", ProductVariantItemController::class);
// Product Variant Items -------------------------------------------------


// Chat ------------------------------------------------- 
Route::post("message/send-message", [UserMessageController::class, 'sendMessage'])->name("message.send-message");
Route::get('message/get-message', [UserMessageController::class, "getMessage"])->name("message.get-message");
Route::get("chat", [ChatController::class, "index"])->name("chat");
// Chat ------------------------------------------------- 



// Orders -------------------------------------------------
Route::get("orders", [OrderController::class, "index"])->name("orders.index");
Route::get("orders/{id}", [OrderController::class, "show"])->name("orders.show");
// Update order status
Route::put("orders/{id}/status", [OrderController::class, "updateStatus"])->name("orders.update.status");
// Orders -------------------------------------------------
