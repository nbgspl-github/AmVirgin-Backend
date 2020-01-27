<?php

use App\Http\Controllers\App\Customer\AuthController as CustomerAuth;
use App\Http\Controllers\App\Customer\Playback\PlaybackController;
use App\Http\Controllers\App\Customer\Playback\TrailerPlayback;
use App\Http\Controllers\App\Customer\PopularPicksController;
use App\Http\Controllers\App\Customer\SlidersController as CustomerSlidersController;
use App\Http\Controllers\App\Customer\TrendController;
use App\Http\Controllers\App\Customer\TwoFactorAuthController as Customer2FAuth;
use App\Http\Controllers\App\Seller\AttributesController as SellerAttributesController;
use App\Http\Controllers\App\Seller\AuthController as SellerAuth;
use App\Http\Controllers\App\Seller\CategoriesController as SellerCategoriesController;
use App\Http\Controllers\App\Seller\CountriesController;
use App\Http\Controllers\App\Seller\CurrenciesController;
use App\Http\Controllers\App\Seller\ProductsController as SellerProductsController;
use App\Http\Controllers\App\Seller\TwoFactorAuthController as Seller2FAuth;
use App\Http\Controllers\App\Shop\CategoryController as ShopCategoryController;
use App\Http\Controllers\App\Shop\ProductsController as ShopProductsController;
use App\Http\Controllers\App\Shop\CartController as ShopCartController;
use Illuminate\Support\Facades\Route;

/**
 * | [Seller] Global collection of middlewares, add more using pipeline character.
 */
$sellerMiddleware = 'auth:seller-api';

/**
 * | [Customer] Global collection of middlewares, add more using pipeline character.
 */
$customerMiddleware = 'auth:customer-api';

/**
 * | Set this to false to clear all middleware bindings.
 */
$useAuthMiddleware = true;

/**
 * Whether to enable middleware or not?
 */
if (!$useAuthMiddleware) {
	$sellerMiddleware = [];
	$customerMiddleware = [];
}

/**
 * | Seller API Route(s)
 */
Route::prefix('seller')->group(function () use ($sellerMiddleware){
	Route::get('/', [Seller2FAuth::class, 'exists'])->name('seller.check');
	Route::get('/profile', [SellerAuth::class, 'profile'])->name('seller.profile')->middleware($sellerMiddleware);
	Route::post('/login', [Seller2FAuth::class, 'login'])->name('seller.login');
	Route::post('/register', [Seller2FAuth::class, 'register'])->name('seller.register');
	Route::post('/logout', [SellerAuth::class, 'logout'])->name('seller.logout')->middleware($sellerMiddleware);
	Route::post('/profile', [SellerAuth::class, 'profile'])->name('seller.logout')->middleware($sellerMiddleware);

	Route::prefix('categories')->group(function (){
		Route::get('/', [SellerCategoriesController::class, 'index'])->name('seller.categories.index');
		Route::get('/{id}/attributes', [SellerAttributesController::class, 'indexFiltered'])->name('seller.attributes.index');
		Route::post('/{id}/attributes', [SellerAttributesController::class, 'store'])->name('seller.attributes.store');
	});

	Route::middleware($sellerMiddleware)->prefix('products')->group(function (){
		Route::get('/', [SellerProductsController::class, 'index'])->name('seller.products.index');
		Route::post('/', [SellerProductsController::class, 'store']);
		Route::get('/{slug}', [SellerProductsController::class, 'singal']);
		Route::post('/edit/{id}', [SellerProductsController::class, 'edit']);
		Route::post('/update/{id}', [SellerProductsController::class, 'update']);
		Route::post('/delete/{id}', [SellerProductsController::class, 'delete']);

	});

	Route::middleware($sellerMiddleware)->prefix('attributes')->group(function (){
		Route::get('/{id}', [SellerAttributesController::class, 'show'])->name('seller.attributes.show');
	});

	Route::prefix('currencies')->group(function (){
		Route::get('/', [CurrenciesController::class, 'index'])->name('seller.currencies.index');
	});

	Route::prefix('countries')->group(function (){
		Route::get('/', [CountriesController::class, 'index'])->name('seller.countries.index');
	});
});


/**
 * | Shop API Route(s)
 */
Route::prefix('shop')->group(function (){
		Route::prefix('category')->group(function (){
		Route::get('/', [ShopCategoryController::class, 'index']);
		});

		Route::prefix('products')->group(function (){
			Route::post('/', [ShopProductsController::class, 'index']);
			Route::post('/details/{id}', [ShopProductsController::class, 'details']);
			Route::post('/categoryby/{id}', [ShopProductsController::class, 'categoryby']);	  
		});

		Route::prefix('cart')->group(function (){
			Route::get('/{cartId}', [ShopCartController::class, 'index']);
			Route::post('/', [ShopCartController::class, 'store']);
			Route::post('/{id}', [ShopCartController::class, 'delete']);
		});
});

/**
 * | Customer API Route(s)
 */
Route::prefix('customer')->group(function () use ($customerMiddleware){
	Route::get('/', [Customer2FAuth::class, 'exists'])->name('customer.check');
	Route::get('/profile', [CustomerAuth::class, 'profile'])->name('customer.profile')->middleware($customerMiddleware);
	Route::post('/login', [Customer2FAuth::class, 'login'])->name('customer.login');
	Route::post('/register', [Customer2FAuth::class, 'register'])->name('customer.register');
	Route::post('/logout', [CustomerAuth::class, 'logout'])->name('customer.logout')->middleware($customerMiddleware);
	Route::get('/profile', [CustomerAuth::class, 'profile'])->name('customer.profile')->middleware($customerMiddleware);

	Route::prefix('sliders')->group(function () use ($customerMiddleware){
		Route::get('/', [CustomerSlidersController::class, 'index'])->name('customer.sliders.index');
	});

	Route::prefix('trending')->group(function (){
		Route::get('/picks', [TrendController::class, 'index'])->name('customer.trending.picks');
	});

	Route::prefix('popular')->group(function (){
		Route::get('', [PopularPicksController::class, 'index'])->name('customer.popular.picks');
	});

	Route::prefix('videos')->group(function (){
		Route::get('/{slug}', [\App\Http\Controllers\App\Customer\VideosController::class, 'show']);
	});

	Route::prefix('playback')->middleware([])->group(function (){
		Route::prefix('trailer')->group(function (){
			Route::get('video/{slug}', [TrailerPlayback::class, 'video']);
			Route::get('tv-series/{slug}', [TrailerPlayback::class, 'series']);
			Route::get('product/{slug}', [TrailerPlayback::class, 'product']);
		});
		Route::get('video', [PlaybackController::class, 'video']);
		Route::get('tv-series', [PlaybackController::class, 'series']);
	});
});
