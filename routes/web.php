<?php

use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\ProfileController;
use App\Jobs\SendWelcomeEmailJob;
use Illuminate\Support\Facades\Route;
use App\Jobs\TestRabbitJob;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, "home"])->name('home');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get("get-slug-url/", [HomeController::class, "getSlugUrl"])->name("get-slug-url");

Route::get("/product", [HomeController::class, "product"])->name("product");

Route::get("/not-found", [HomeController::class, "notFound"])->name("not-found");




// Product by search
Route::get("/product-by-search", [HomeController::class, "productBySearch"])->name("product-by-search");

// More products by types 
Route::get("/more-products-by-types", [HomeController::class, "moreProductsByType"])->name("more-products-by-type");


// More Products by brands 
Route::get("/more-products-by-brands", [HomeController::class, "moreProductsByBrand"])->name("more-products-by-brand");

// More Products by Shop 

// More Products by flash sale 
Route::get("/more-products-by-flash-sale", [HomeController::class, "moreProductsByFlashSale"])->name("more-products-by-flash-sale");


// View Shop Page
Route::get("/shop", [HomeController::class, "shop"])->name("shop");

require __DIR__ . '/auth.php';
