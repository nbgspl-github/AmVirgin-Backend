<?php

use App\Classes\Str;
use App\Http\Controllers\App\Customer\AuthController;
use App\Http\Controllers\App\Customer\Cart\CustomerWishlistController;
use App\Http\Controllers\App\Customer\Cart\QuoteController;
use App\Http\Controllers\App\Customer\CitiesController;
use App\Http\Controllers\App\Customer\CountriesController;
use App\Http\Controllers\App\Customer\Playback\PlaybackController;
use App\Http\Controllers\App\Customer\Playback\TrailerPlayback;
use App\Http\Controllers\App\Customer\PopularPicksController;
use App\Http\Controllers\App\Customer\ProductsController;
use App\Http\Controllers\App\Customer\ShippingAddressesController;
use App\Http\Controllers\App\Customer\ShopSlidersController;
use App\Http\Controllers\App\Customer\SlidersController;
use App\Http\Controllers\App\Customer\StatesController;
use App\Http\Controllers\App\Customer\TrendController;
use App\Http\Controllers\App\Customer\TwoFactorAuthController;
use App\Http\Controllers\App\Customer\Shop\HomePageController as ShopHomeController;
use App\Http\Controllers\App\Customer\Entertainment\HomePageController as EntertainmentHomeController;
use App\Http\Controllers\App\Customer\SubscriptionController;
use App\Http\Controllers\App\Customer\GlobalSearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\Seller\OrdersController;

Route::get('/', [TwoFactorAuthController::class, 'exists'])->name('customer.check');
Route::get('/profile', [AuthController::class, 'profile'])->name('customer.profile')->middleware('auth:customer-api');
Route::post('/login', [TwoFactorAuthController::class, 'login'])->name('customer.login');
Route::post('/register', [TwoFactorAuthController::class, 'register'])->name('customer.register');
Route::post('/logout', [AuthController::class, 'logout'])->name('customer.logout')->middleware('auth:customer-api');
Route::get('/profile', [AuthController::class, 'profile'])->name('customer.profile')->middleware('auth:customer-api');

Route::prefix('videos')->group(function () {
	Route::get('/{id}', [\App\Http\Controllers\App\Customer\VideosController::class, 'show']);
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

Route::prefix('cart')->middleware([])->group(function () {
	Route::post('add', [QuoteController::class, 'add']);
	Route::put('update', [QuoteController::class, 'update']);
	Route::post('remove', [QuoteController::class, 'remove']);
	Route::post('destroy', [QuoteController::class, 'destroy']);
	Route::get('retrieve', [QuoteController::class, 'retrieve']);
	Route::put('wishlist/{productId}', [QuoteController::class, 'moveToWishlist']);
	Route::post('submit', [QuoteController::class, 'submit']);
});

Route::prefix('wishlist')->middleware('auth:customer-api')->group(function () {
	Route::get('/', [CustomerWishlistController::class, 'index']);
	Route::put('/{productId}', [CustomerWishlistController::class, 'store']);
	Route::delete('/{product}', [CustomerWishlistController::class, 'delete']);
	Route::delete('/{productId}', [CustomerWishlistController::class, 'move']);
	Route::put('cart/{productId}', [CustomerWishlistController::class, 'moveToCart']);

});

Route::prefix('shop')->group(function () {
	Route::get('homepage', [ShopHomeController::class, 'index']);
	Route::prefix('deals')->group(function () {
		Route::get('', [ShopHomeController::class, 'showAllDeals']);
	});
});

Route::prefix('entertainment')->group(function () {
	Route::get('homepage', [EntertainmentHomeController::class, 'index']);
	Route::prefix('section')->group(function () {
		Route::get('{id}', [EntertainmentHomeController::class, 'showAllItemsInSection']);
	});

	Route::prefix('trending')->group(function () {
		Route::get('/', [EntertainmentHomeController::class, 'trendingNow']);
	});

	Route::prefix('recommended')->group(function () {
		Route::get('/', [EntertainmentHomeController::class, 'recommendedVideo']);
	});
});

Route::prefix('addresses')->middleware('auth:customer-api')->group(function () {
	Route::get('/', [ShippingAddressesController::class, 'index']);
	Route::post('/', [ShippingAddressesController::class, 'store']);
	Route::put('/{id}', [ShippingAddressesController::class, 'update']);
	Route::delete('/{id}', [ShippingAddressesController::class, 'delete']);
});

Route::prefix('countries')->group(function () {
	Route::get('/', [CountriesController::class, 'index'])->name('seller.countries.index');
	Route::get('{countryId}/states', [StatesController::class, 'index'])->name('seller.states.index');
	Route::get('states/{stateId}/cities', [CitiesController::class, 'index'])->name('seller.states.index');
});

Route::prefix('subscriptions')->group(function () {
	Route::get(Str::Empty, [SubscriptionController::class, 'index']);
});

Route::prefix('search')->group(function () {
	Route::get(Str::Empty, [GlobalSearchController::class, 'search']);
});

Route::prefix('orders')->group(function () {
	Route::get(Str::Empty, [OrdersController::class, 'getorders']);
	Route::get('{param}', [OrdersController::class, 'getorderdetails']);
});

Route::prefix('recent')->middleware('auth:customer-api')->group(function () {
	Route::get('/', []);
});