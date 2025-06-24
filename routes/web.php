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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');





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


Route::get('/test-rabbit', function () {
    $user = User::where("email", "tranmy0122english@gmail.com")->first();
    SendWelcomeEmailJob::dispatch($user);
    return ' Đã gửi job vào RabbitMQ!';
});



require __DIR__ . '/auth.php';
