<?php

use App\Http\Controllers\Api\Customer\AuthController;
use App\Http\Controllers\Api\Customer\Cart\QuoteController;
use App\Http\Controllers\Api\Customer\Cart\WishlistController;
use App\Http\Controllers\Api\Customer\CatalogController;
use App\Http\Controllers\Api\Customer\CitiesController;
use App\Http\Controllers\Api\Customer\CountriesController;
use App\Http\Controllers\Api\Customer\Entertainment\HomePageController as EntertainmentHomeController;
use App\Http\Controllers\Api\Customer\GlobalSearchController;
use App\Http\Controllers\Api\Customer\ShippingAddressesController;
use App\Http\Controllers\Api\Customer\Shop\HomePageController as ShopHomeController;
use App\Http\Controllers\Api\Customer\Shop\ProductRatingController;
use App\Http\Controllers\Api\Customer\StatesController;
use App\Http\Controllers\Api\Customer\SubscriptionController;
use App\Http\Controllers\Api\Customer\TwoFactorAuthController;
use App\Library\Utils\Extensions\Str;
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
	Route::post('contact-us', [\App\Http\Controllers\Api\Customer\ContactUsController::class, 'store']);
});

Route::prefix('videos')->group(function () {
	Route::get('/{id}', [\App\Http\Controllers\Api\Customer\VideosController::class, 'show']);
});

Route::prefix('categories')->group(function () {
	Route::get('/', [\App\Http\Controllers\Api\Customer\CategoryController::class, 'index']);
	Route::get('filters', [\App\Http\Controllers\Api\Customer\FilterController::class, 'show']);
});

Route::prefix('sessions')->group(function () {
	Route::get('/start', [\App\Http\Controllers\Api\Customer\Session\SessionController::class, 'create']);
	Route::get('/{sessionId}', [\App\Http\Controllers\Api\Customer\Session\SessionController::class, 'check']);
});

Route::prefix('products')->group(function () {
	Route::get('/', [CatalogController::class, 'index']);
	Route::get('/sorts', [\App\Http\Controllers\Api\Customer\SortController::class, 'sorts']);
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

Route::prefix('wishlist')->middleware('auth:customer-api')->group(function () {
	Route::get(Str::Empty, [WishlistController::class, 'index']);
	Route::put('{productId}', [WishlistController::class, 'store']);
	Route::delete('{product}', [WishlistController::class, 'delete']);
	Route::delete('{productId}', [WishlistController::class, 'move']);
	Route::put('cart/{productId}', [WishlistController::class, 'moveToCart']);

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

	Route::get('filters', [\App\Http\Controllers\Api\Customer\Entertainment\ContentFilterController::class, 'index']);
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
	Route::get(Str::Empty, [\App\Http\Controllers\Api\Customer\Orders\OrderController::class, 'index']);
	Route::prefix('items')->group(function () {
		Route::post('{item}/return', [\App\Http\Controllers\Api\Customer\Orders\ReturnController::class, 'return']);
	});
	Route::get('{order}', [\App\Http\Controllers\Api\Customer\Orders\OrderController::class, 'show']);
	Route::get('{order}/track', [\App\Http\Controllers\Api\Customer\Orders\OrderController::class, 'track']);
	Route::put('{order}/cancel', [\App\Http\Controllers\Api\Customer\Orders\CancellationController::class, 'cancel']);
	Route::prefix('{order}')->group(static function () {
		Route::put('return-now', [\App\Http\Controllers\Api\Customer\Orders\StatusController::class, 'return']);
	});
});

Route::prefix('recent')->middleware('auth:customer-api')->group(function () {
	Route::get('/', []);
});

Route::prefix('brands')->group(function () {
	Route::get('{id}', [\App\Http\Controllers\Api\Customer\Shop\BrandController::class, 'show']);
});

Route::prefix('watch-later')->group(function () {
	Route::post(Str::Empty, [\App\Http\Controllers\Api\Customer\VideosController::class, 'addInWatchLater'])->name('customer.addInWatchLater')->middleware('auth:customer-api');

	Route::delete('/remove/{id}', [\App\Http\Controllers\Api\Customer\VideosController::class, 'removeWatchLater'])->name('customer.removeWatchLater')->middleware('auth:customer-api');

	Route::get(Str::Empty, [\App\Http\Controllers\Api\Customer\VideosController::class, 'getWatchLaterVideo'])->name('customer.getWatchLaterVideo')->middleware('auth:customer-api');
});

Route::prefix('languages')->group(static function () {
	Route::get(Str::Empty, [\App\Http\Controllers\Api\Customer\Entertainment\LanguageListController::class, 'index']);
});

Route::prefix('genres')->group(static function () {
	Route::get(Str::Empty, [\App\Http\Controllers\Api\Customer\Entertainment\GenreListController::class, 'index']);
});

Route::prefix('news-categories')->group(static function () {
	Route::get(Str::Empty, [\App\Http\Controllers\Api\Customer\News\Categories\CategoryController::class, 'index']);
	Route::prefix('{id}')->group(static function () {
		Route::get(Str::Empty, [\App\Http\Controllers\Api\Customer\News\Categories\CategoryController::class, 'show']);
	});
});

Route::prefix('news')->group(static function () {
	Route::get(Str::Empty, [\App\Http\Controllers\Api\Customer\News\Categories\NewsController::class, 'index']);
	Route::get('{item}/show', [\App\Http\Controllers\Api\Customer\News\Categories\NewsController::class, 'show']);

	Route::prefix('articles')->group(static function () {
		Route::get(Str::Empty, [\App\Http\Controllers\Api\Customer\News\Articles\ArticleController::class, 'index']);
		Route::get('{article}', [\App\Http\Controllers\Api\Customer\News\Articles\ArticleController::class, 'show']);
	});
});

Route::prefix('test-routes')->group(function () {
	Route::get('payment', [QuoteController::class, 'test']);
});