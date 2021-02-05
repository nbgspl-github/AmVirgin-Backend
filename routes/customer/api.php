<?php

use App\Http\Modules\Customer\Controllers\Api\Auth\ExistenceController;
use App\Http\Modules\Customer\Controllers\Api\Cart\QuoteController;
use App\Http\Modules\Customer\Controllers\Api\Cart\WishlistController;
use App\Http\Modules\Customer\Controllers\Api\Entertainment\HomeController as EntertainmentHomeController;
use App\Http\Modules\Customer\Controllers\Api\Orders\CitiesController;
use App\Http\Modules\Customer\Controllers\Api\Orders\CountriesController;
use App\Http\Modules\Customer\Controllers\Api\Shared\SearchController;
use App\Http\Modules\Customer\Controllers\Api\Shared\StatesController;
use App\Http\Modules\Customer\Controllers\Api\Shop\AddressController;
use App\Http\Modules\Customer\Controllers\Api\Shop\CatalogController;
use App\Http\Modules\Customer\Controllers\Api\Shop\HomePageController as ShopHomeController;
use App\Http\Modules\Customer\Controllers\Api\Shop\ProductRatingController;
use App\Http\Modules\Customer\Controllers\Api\Subscription\PlansController;
use App\Library\Utils\Extensions\Str;
use Illuminate\Support\Facades\Route;

Route::prefix(Str::Empty)->group(function () {
	Route::get(Str::Empty, [ExistenceController::class, 'exists']);
	Route::post('login', [\App\Http\Modules\Customer\Controllers\Api\Auth\LoginController::class, 'login']);
	Route::post('login/social', [\App\Http\Modules\Customer\Controllers\Api\Auth\SocialLoginController::class, 'login']);
	Route::post('logout', [\App\Http\Modules\Customer\Controllers\Api\Auth\LoginController::class, 'logout']);
	Route::post('register', [\App\Http\Modules\Customer\Controllers\Api\Auth\RegisterController::class, 'register']);
	Route::prefix('profile')->group(function () {
		Route::get(Str::Empty, [\App\Http\Modules\Customer\Controllers\Api\Auth\ProfileController::class, 'show']);
		Route::put(Str::Empty, [\App\Http\Modules\Customer\Controllers\Api\Auth\ProfileController::class, 'update']);
		Route::post('avatar', [\App\Http\Modules\Customer\Controllers\Api\Auth\AvatarController::class, 'update']);
		Route::put('password', [\App\Http\Modules\Customer\Controllers\Api\Auth\PasswordController::class, 'update']);
	});
	Route::post('contact-us', [\App\Http\Modules\Customer\Controllers\Api\Shared\ContactUsController::class, 'store']);
	Route::get('password/reset', [\App\Http\Modules\Customer\Controllers\Api\Auth\PasswordResetController::class, 'initiate']);
});

Route::prefix('videos')->group(function () {
	Route::get('{video}', [\App\Http\Modules\Customer\Controllers\Api\Entertainment\VideoController::class, 'show']);
});

Route::prefix('categories')->group(function () {
	Route::get('/', [\App\Http\Modules\Customer\Controllers\Api\Shop\CategoryController::class, 'index']);
	Route::get('{category}/filters', [\App\Http\Modules\Customer\Controllers\Api\Shop\FilterController::class, 'show']);
	Route::get('{category}/products', [CatalogController::class, 'index']);
});

Route::prefix('sessions')->group(function () {
	Route::get('/start', [\App\Http\Modules\Customer\Controllers\Api\Session\SessionController::class, 'create']);
	Route::get('/{sessionId}', [\App\Http\Modules\Customer\Controllers\Api\Session\SessionController::class, 'check']);
});

Route::prefix('products')->group(function () {
	Route::get('/sorts', [\App\Http\Modules\Customer\Controllers\Api\Shop\SortController::class, 'sorts']);
	Route::prefix('{product}')->group(static function () {
		Route::get(Str::Empty, [CatalogController::class, 'show']);
		Route::prefix('reviews')->group(static function () {
			Route::get(Str::Empty, [ProductRatingController::class, 'show']);
			Route::post('{order}', [ProductRatingController::class, 'store']);
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

Route::prefix('wishlist')->group(function () {
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
		Route::get('{section}', [EntertainmentHomeController::class, 'showAllItemsInSection']);
	});

	Route::prefix('trending')->group(function () {
		Route::get('/', [EntertainmentHomeController::class, 'trendingNow']);
	});

	Route::prefix('recommended')->group(function () {
		Route::get('/', [EntertainmentHomeController::class, 'recommendedVideo']);
	});

	Route::get('filters', [\App\Http\Modules\Customer\Controllers\Api\Entertainment\ContentFilterController::class, 'index']);
});

Route::prefix('addresses')->middleware('auth:customer-api')->group(function () {
	Route::get('/', [AddressController::class, 'index']);
	Route::post('/', [AddressController::class, 'store']);
	Route::put('/{id}', [AddressController::class, 'update']);
	Route::delete('/{id}', [AddressController::class, 'delete']);
});

Route::prefix('countries')->group(function () {
	Route::get('/', [CountriesController::class, 'index'])->name('seller.countries.index');
	Route::get('{countryId}/states', [StatesController::class, 'index'])->name('seller.states.index');
	Route::get('states/{stateId}/cities', [CitiesController::class, 'index'])->name('seller.states.index');
});

Route::prefix('subscriptions')->group(function () {
	Route::get(Str::Empty, [PlansController::class, 'index']);
	Route::prefix('checkout')->group(function () {
		Route::get('{plan}', [\App\Http\Modules\Customer\Controllers\Api\Subscription\SubscriptionController::class, 'checkout']);
		Route::get(Str::Empty, [\App\Http\Modules\Customer\Controllers\Api\Subscription\SubscriptionController::class, 'submit']);
	});
});

Route::prefix('search')->group(function () {
	Route::get(Str::Empty, [SearchController::class, 'search']);
});

Route::prefix('orders')->middleware('auth:customer-api')->group(function () {
	Route::get(Str::Empty, [\App\Http\Modules\Customer\Controllers\Api\Orders\OrderController::class, 'index']);
	Route::prefix('items')->group(function () {
		Route::post('{item}/return', [\App\Http\Modules\Customer\Controllers\Api\Orders\ReturnController::class, 'return']);
	});
	Route::get('{order}', [\App\Http\Modules\Customer\Controllers\Api\Orders\OrderController::class, 'show']);
	Route::get('{order}/track', [\App\Http\Modules\Customer\Controllers\Api\Orders\OrderController::class, 'track']);
	Route::put('{order}/cancel', [\App\Http\Modules\Customer\Controllers\Api\Orders\CancellationController::class, 'cancel']);
});

Route::prefix('recent')->middleware('auth:customer-api')->group(function () {
	Route::get('/', []);
});

Route::prefix('brands')->group(function () {
	Route::get('{id}', [\App\Http\Modules\Customer\Controllers\Api\Shop\BrandController::class, 'show']);
});

Route::prefix('watch-later')->group(function () {
	Route::get(Str::Empty, [\App\Http\Modules\Customer\Controllers\Api\Entertainment\WatchLaterController::class, 'index']);
	Route::get('{video}', [\App\Http\Modules\Customer\Controllers\Api\Entertainment\WatchLaterController::class, 'store']);
	Route::delete('{video}', [\App\Http\Modules\Customer\Controllers\Api\Entertainment\WatchLaterController::class, 'delete']);
});

Route::prefix('languages')->group(static function () {
	Route::get(Str::Empty, [\App\Http\Modules\Customer\Controllers\Api\Entertainment\LanguageListController::class, 'index']);
});

Route::prefix('genres')->group(static function () {
	Route::get(Str::Empty, [\App\Http\Modules\Customer\Controllers\Api\Entertainment\GenreListController::class, 'index']);
});

Route::prefix('news')->group(static function () {
	Route::prefix('categories')->group(static function () {
		Route::get(Str::Empty, [\App\Http\Modules\Customer\Controllers\Api\News\Categories\CategoryController::class, 'index']);
		Route::prefix('{category}')->group(static function () {
			Route::get(Str::Empty, [\App\Http\Modules\Customer\Controllers\Api\News\Categories\CategoryController::class, 'show']);
		});
	});
	Route::prefix('articles')->group(static function () {
		Route::get(Str::Empty, [\App\Http\Modules\Customer\Controllers\Api\News\Articles\ArticleController::class, 'index']);
		Route::get('{article}', [\App\Http\Modules\Customer\Controllers\Api\News\Articles\ArticleController::class, 'show']);
	});
});