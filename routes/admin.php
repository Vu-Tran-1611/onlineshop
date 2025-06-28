<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductFromVendorController;
use App\Http\Controllers\Admin\ProductManagementController;
use App\Http\Controllers\admin\ProfileController;
use App\Http\Controllers\admin\SliderController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\admin\TopBannerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Backend\FlashSellController;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Controllers\Vendor\ProductImageGalleryController;
use App\Http\Controllers\Vendor\ProductVariantController;
use App\Http\Controllers\Vendor\ProductVariantItemController;
use App\Http\Controllers\Vendor\ProductController;
use App\Http\Controllers\backend\CouponController;
// Profile -------------------------------------------------
Route::post("profile-update", [ProfileController::class, "profileUpdate"])->name("profile.profile-update");

Route::post("password-update", [ProfileController::class, "passwordUpdate"])->name("profile.password-update");

Route::get("profile", [ProfileController::class, "index"])->name("profile");
// Profile -------------------------------------------------

// User -------------------------------------------------
Route::resource('user', UserController::class);
// User -------------------------------------------------


// Slider ------------------------------------------------
// Change Status 
Route::put("slider/{id}/change-status", [SliderController::class, "changeStatus"])->name("slider.change-status");
// Change Serial 
Route::put("slider/{id}/change-serial", [SliderController::class, "changeserial"])->name("slider.change-serial");
Route::resource("slider", SliderController::class);
// Slider ------------------------------------------------



// Brand ------------------------------------------------
Route::get("brand/{id}/get-category", [BrandController::class, "getCategory"])->name("brand.get-category");
Route::put("brand/{id}/change-status", [BrandController::class, "changeStatus"])->name("brand.change-status");
Route::put("brand/{id}/change-featured", [BrandController::class, "changeFeatured"])->name("brand.change-featured");
Route::resource("brand", BrandController::class);
// Brand ------------------------------------------------



// Category ------------------------------------------------
Route::put("category/{id}/change-status", [CategoryController::class, "changeStatus"])->name("category.change-status");
Route::resource("category", CategoryController::class);
// Category ------------------------------------------------


//Sub Category ------------------------------------------------
Route::put("sub-category/{id}/change-status", [SubCategoryController::class, "changeStatus"])->name("sub-category.change-status");
Route::resource("sub-category", SubCategoryController::class);
//Sub Category ------------------------------------------------


// Products From Vendors ------------------------------------------------
Route::get("product-from-vendor/index", [ProductFromVendorController::class, "index"])->name("product_from_vendor.index");
// Products From Vendors ------------------------------------------------


// Product Management ------------------------------------------------------------------------


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

Route::post("category/get-sub-categories", function (Request $request) {
    $categoryID = $request->categoryID;
    $subCategories = Category::findOrFail($categoryID)->subCategories;
    return response(["subCategories" => $subCategories]);
})->name("category.get-sub-categories");

Route::put("product/change-status", [ProductManagementController::class, "changeProductApproved"])->name("product.change_product_approved");
Route::put("product/{id}/change-status", [ProductManagementController::class, "changeStatus"])->name("product.change_status");
Route::put("product/change-type", [ProductManagementController::class, "changeType"])->name("product.change_type");


// Product -------------------------------------------------

Route::put("product/{id}/change-status", [ProductController::class, "changeStatus"])->name("product.change_status");
Route::put("product/change-type", [ProductController::class, "changeType"])->name("product.change_type");
Route::resource("product", ProductController::class);
// Product -------------------------------------------------

// Product Management ------------------------------------------------------------------------




// Flash Sell  -------------------------------------------------
Route::delete("flash-sell/item/{id}/delete", [FlashSellController::class, "destroy"])->name("flash_sell.item.destroy");
Route::put("flash-sell/item/{id}/change-status", [FlashSellController::class, "changeStatus"])->name("flash_sell.item.change_status");
Route::put("flash-sell/store", [FlashSellController::class, "store"])->name("flash_sell.store");
Route::get("flash-sell/index", [FlashSellController::class, "index"])->name("flash_sell.index");
Route::put("flash-sell/update", [FlashSellController::class, "update"])->name("flash_sell.update");
// Flash Sell  -------------------------------------------------



// Coupons  -------------------------------------------------
Route::put("coupons/{id}/change-status", [CouponController::class, "changeStatus"])->name("coupons.change_status");
Route::resource("coupons", CouponController::class);

// Coupons  -------------------------------------------------



// Top Banner -------------------------------------------------
Route::put("top-banner/{id}/change-status", [TopBannerController::class, "changeStatus"])->name("top-banner.change-status");
Route::resource("top-banner", TopBannerController::class);
// Top Banner -------------------------------------------------
