<?php

use App\Classes\Str;
use App\Http\Controllers\App\Customer\AuthController;
use App\Http\Controllers\App\Customer\Cart\CustomerWishlistController;
use App\Http\Controllers\App\Customer\Cart\QuoteController;
use App\Http\Controllers\App\Customer\CatalogController;
use App\Http\Controllers\App\Customer\CitiesController;
use App\Http\Controllers\App\Customer\CountriesController;
use App\Http\Controllers\App\Customer\Entertainment\HomePageController as EntertainmentHomeController;
use App\Http\Controllers\App\Customer\GlobalSearchController;
use App\Http\Controllers\App\Customer\ShippingAddressesController;
use App\Http\Controllers\App\Customer\Shop\HomePageController as ShopHomeController;
use App\Http\Controllers\App\Customer\Shop\ProductRatingController;
use App\Http\Controllers\App\Customer\StatesController;
use App\Http\Controllers\App\Customer\SubscriptionController;
use App\Http\Controllers\App\Customer\TwoFactorAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix(Str::Empty)->group(function () {
	Route::get(Str::Empty, [TwoFactorAuthController::class, 'exists']);
	Route::post('login', [TwoFactorAuthController::class, 'login']);
	Route::post('login/social', [TwoFactorAuthController::class, 'socialLogin']);
	Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:customer-api');
	Route::post('register', [TwoFactorAuthController::class, 'register']);
	Route::prefix('profile')->middleware('auth:customer-api')->group(function () {
		Route::get(Str::Empty, [AuthController::class, 'profile']);
		Route::post('avatar', [AuthController::class, 'updateAvatar']);
		Route::put(Str::Empty, [AuthController::class, 'updateProfile']);
		Route::put('password', [AuthController::class, 'updatePassword']);
	});
	Route::post('contact-us', [\App\Http\Controllers\App\Customer\ContactUsController::class, 'store']);
});

Route::prefix('videos')->group(function () {
	Route::get('/{id}', [\App\Http\Controllers\App\Customer\VideosController::class, 'show']);
});

Route::prefix('categories')->group(function () {
	Route::get('/', [\App\Http\Controllers\App\Customer\CategoryController::class, 'index']);
	Route::get('filters', [\App\Http\Controllers\App\Customer\FilterController::class, 'show']);
});

Route::prefix('sessions')->group(function () {
	Route::get('/start', [\App\Http\Controllers\App\Customer\Session\SessionController::class, 'create']);
	Route::get('/{sessionId}', [\App\Http\Controllers\App\Customer\Session\SessionController::class, 'check']);
});

Route::prefix('products')->group(function () {
	Route::get('/', [CatalogController::class, 'index']);
	Route::get('/sorts', [\App\Http\Controllers\App\Customer\SortController::class, 'sorts']);
	Route::prefix('{id}')->group(static function () {
		Route::get(Str::Empty, [CatalogController::class, 'show']);
		Route::prefix('reviews')->group(static function () {
			Route::get(Str::Empty, [ProductRatingController::class, 'show']);
			Route::post(Str::Empty, [ProductRatingController::class, 'store']);
		});
	});
});

Route::prefix('cart')->middleware([])->group(function () {
	Route::post('add', [QuoteController::class, 'add']);
	Route::put('update', [QuoteController::class, 'update']);
	Route::post('remove', [QuoteController::class, 'remove']);
	Route::post('destroy', [QuoteController::class, 'destroy']);
	Route::get('retrieve', [QuoteController::class, 'retrieve']);
	Route::put('wishlist/{productId}', [QuoteController::class, 'moveToWishlist'])->middleware('auth:customer-api');
	Route::post('checkout', [QuoteController::class, 'checkout'])->middleware('auth:customer-api');
	Route::post('submit', [QuoteController::class, 'submit'])->middleware('auth:customer-api');
	Route::post('{order}/verify', [QuoteController::class, 'verify'])->middleware('auth:customer-api');
});

Route::prefix('checkout/{order}')->group(function () {
	Route::post('initiate', [\App\Http\Controllers\App\Customer\Cart\CheckoutController::class, 'initiate']);
	Route::post('verify', [\App\Http\Controllers\App\Customer\Cart\CheckoutController::class, 'verify']);
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

	Route::get('filters', [\App\Http\Controllers\App\Customer\Entertainment\ContentFilterController::class, 'index']);
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

Route::prefix('orders')->middleware('auth:customer-api')->group(function () {
	Route::get(Str::Empty, [\App\Http\Controllers\App\Customer\OrderController::class, 'index']);
	Route::prefix('{id}')->group(function () {
		Route::prefix('items')->group(static function () {
			Route::get('{item}/return', [\App\Http\Controllers\App\Customer\Orders\ReturnController::class, 'return']);
		});
	});
	Route::get('{id}', [\App\Http\Controllers\App\Customer\OrderController::class, 'show']);
	Route::get('{id}/track', [\App\Http\Controllers\App\Customer\OrderController::class, 'track']);
	Route::put('{id}/cancel', [\App\Http\Controllers\App\Customer\OrderController::class, 'cancel']);
	Route::put('{id}/return', [\App\Http\Controllers\App\Customer\OrderController::class, 'return']);
	Route::prefix('{order}')->group(static function () {
		Route::put('return-now', [\App\Http\Controllers\App\Customer\Orders\StatusController::class, 'return']);
	});
});

Route::prefix('recent')->middleware('auth:customer-api')->group(function () {
	Route::get('/', []);
});

Route::prefix('brands')->group(function () {
	Route::get('{id}', [\App\Http\Controllers\App\Customer\Shop\BrandController::class, 'show']);
});

Route::prefix('watch-later')->group(function () {
	Route::post(Str::Empty, [\App\Http\Controllers\App\Customer\VideosController::class, 'addInWatchLater'])->name('customer.addInWatchLater')->middleware('auth:customer-api');

	Route::delete('/remove/{id}', [\App\Http\Controllers\App\Customer\VideosController::class, 'removeWatchLater'])->name('customer.removeWatchLater')->middleware('auth:customer-api');

	Route::get(Str::Empty, [\App\Http\Controllers\App\Customer\VideosController::class, 'getWatchLaterVideo'])->name('customer.getWatchLaterVideo')->middleware('auth:customer-api');
});

Route::prefix('languages')->group(static function () {
	Route::get(Str::Empty, [\App\Http\Controllers\App\Customer\Entertainment\LanguageListController::class, 'index']);
});

Route::prefix('genres')->group(static function () {
	Route::get(Str::Empty, [\App\Http\Controllers\App\Customer\Entertainment\GenreListController::class, 'index']);
});

Route::prefix('news-categories')->group(static function () {
	Route::get(Str::Empty, [\App\Http\Controllers\App\Customer\News\Categories\CategoryController::class, 'index']);
	Route::prefix('{id}')->group(static function () {
		Route::get(Str::Empty, [\App\Http\Controllers\App\Customer\News\Categories\CategoryController::class, 'show']);
	});
});

Route::prefix('news')->group(static function () {
	Route::get(Str::Empty, [\App\Http\Controllers\App\Customer\News\Categories\NewsController::class, 'index']);
	Route::get('{id}/show', [\App\Http\Controllers\App\Customer\News\Categories\NewsController::class, 'show']);
});

Route::prefix('test-routes')->group(function () {
	Route::get('payment', [QuoteController::class, 'test']);
});