<?php

use App\Http\Controllers\App\Customer\AuthController;
use App\Http\Controllers\App\Customer\Cart\CustomerWishlistController;
use App\Http\Controllers\App\Customer\Cart\QuoteController;
use App\Http\Controllers\App\Customer\Playback\PlaybackController;
use App\Http\Controllers\App\Customer\Playback\TrailerPlayback;
use App\Http\Controllers\App\Customer\PopularPicksController;
use App\Http\Controllers\App\Customer\ProductsController;
use App\Http\Controllers\App\Customer\SlidersController;
use App\Http\Controllers\App\Customer\TrendController;
use App\Http\Controllers\App\Customer\TwoFactorAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TwoFactorAuthController::class, 'exists'])->name('customer.check');
Route::get('/profile', [AuthController::class, 'profile'])->name('customer.profile')->middleware('auth:customer-api');
Route::post('/login', [TwoFactorAuthController::class, 'login'])->name('customer.login');
Route::post('/register', [TwoFactorAuthController::class, 'register'])->name('customer.register');
Route::post('/logout', [AuthController::class, 'logout'])->name('customer.logout')->middleware('auth:customer-api');
Route::get('/profile', [AuthController::class, 'profile'])->name('customer.profile')->middleware('auth:customer-api');

Route::prefix('sliders')->group(function () {
	Route::get('/', [SlidersController::class, 'index'])->name('customer.sliders.index');
});

Route::prefix('trending')->group(function () {
	Route::get('/picks', [TrendController::class, 'index'])->name('customer.trending.picks');
});

Route::prefix('popular')->group(function () {
	Route::get('', [PopularPicksController::class, 'index'])->name('customer.popular.picks');
});

Route::prefix('videos')->group(function () {
	Route::get('/{slug}', [\App\Http\Controllers\App\Customer\VideosController::class, 'show']);
});

Route::prefix('categories')->group(function () {
	Route::get('/', [\App\Http\Controllers\App\Seller\CategoriesController::class, 'index']);
});

Route::prefix('sessions')->group(function () {
	Route::get('/start', [\App\Http\Controllers\App\Customer\Session\SessionController::class, 'create']);
	Route::get('/{sessionId}', [\App\Http\Controllers\App\Customer\Session\SessionController::class, 'check']);
});

Route::prefix('products')->group(function () {
	Route::get('/', [ProductsController::class, 'index']);
	Route::get('/sorts', [ProductsController::class, 'sortsIndex']);
	Route::get('{id}', [ProductsController::class, 'show'])->name('seller.products.show');
});

Route::prefix('playback')->middleware([])->group(function () {
	Route::prefix('trailer')->group(function () {
		Route::get('video/{slug}', [TrailerPlayback::class, 'video']);
		Route::get('tv-series/{slug}', [TrailerPlayback::class, 'series']);
		Route::get('product/{slug}', [TrailerPlayback::class, 'product']);
	});
	Route::get('video', [PlaybackController::class, 'video']);
	Route::get('tv-series', [PlaybackController::class, 'series']);
});

Route::prefix('cart')->middleware([])->group(function () {
	Route::post('add', [QuoteController::class, 'add']);
	Route::post('remove', [QuoteController::class, 'remove']);
	Route::post('destroy', [QuoteController::class, 'destroy']);
	Route::get('retrieve', [QuoteController::class, 'retrieve']);
	Route::put('wishlist/{productId}', [QuoteController::class, 'moveToWishlist']);
});

Route::prefix('wishlist')->middleware('auth:customer-api')->group(function () {
	Route::get('/', [CustomerWishlistController::class, 'index']);
	Route::put('cart/{productId}', [CustomerWishlistController::class, 'moveToCart']);
	Route::put('/{productId}', [CustomerWishlistController::class, 'store']);
	Route::delete('/{productId}', [CustomerWishlistController::class, 'move']);
});