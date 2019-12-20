<?php

use App\Http\Controllers\App\Customer\AuthController as CustomerAuth;
use App\Http\Controllers\App\Customer\SlidersController as CustomerSlidersController;
use App\Http\Controllers\App\Customer\TrendController;
use App\Http\Controllers\App\Customer\TwoFactorAuthController as CustomerTwoFactorAuth;
use App\Http\Controllers\App\Seller\AttributesController as SellerAttributesController;
use App\Http\Controllers\App\Seller\AuthController as SellerAuth;
use App\Http\Controllers\App\Seller\CategoriesController as SellerCategoriesController;
use App\Http\Controllers\App\Seller\ProductsController as SellerProductsController;
use App\Http\Controllers\App\Seller\TwoFactorAuthController as SellerTwoFactorAuth;
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

if (!$useAuthMiddleware) {
	$sellerMiddleware = [];
	$customerMiddleware = [];
}

Route::prefix('seller')->group(function () use ($sellerMiddleware){
	Route::get('/', [SellerTwoFactorAuth::class, 'exists'])->name('seller.check');
	Route::get('/profile', [SellerAuth::class, 'profile'])->name('seller.profile')->middleware($sellerMiddleware);
	Route::post('/login', [SellerTwoFactorAuth::class, 'login'])->name('seller.login');
	Route::post('/register', [SellerTwoFactorAuth::class, 'register'])->name('seller.register');
	Route::post('/logout', [SellerAuth::class, 'logout'])->name('seller.logout')->middleware($sellerMiddleware);

	Route::middleware($sellerMiddleware)->prefix('categories')->group(function (){
		Route::get('/', [SellerCategoriesController::class, 'index'])->name('seller.categories.index');
		Route::get('/{id}/attributes', [SellerAttributesController::class, 'indexFiltered'])->name('seller.attributes.index');
		Route::post('/{id}/attributes', [SellerAttributesController::class, 'store'])->name('seller.attributes.store');
	});

	Route::middleware($sellerMiddleware)->prefix('products')->group(function (){
		Route::get('/', [SellerProductsController::class, 'index'])->name('seller.products.index');
	});

	Route::middleware($sellerMiddleware)->prefix('attributes')->group(function (){
		Route::get('/{id}', [SellerAttributesController::class, 'show'])->name('seller.attributes.show');
	});
});

Route::prefix('customer')->group(function () use ($customerMiddleware){
	Route::get('/', [CustomerTwoFactorAuth::class, 'exists'])->name('customer.check');
	Route::get('/profile', [CustomerAuth::class, 'profile'])->name('customer.profile')->middleware($customerMiddleware);
	Route::post('/login', [CustomerTwoFactorAuth::class, 'login'])->name('customer.login');
	Route::post('/register', [CustomerTwoFactorAuth::class, 'register'])->name('customer.register');
	Route::post('/logout', [CustomerAuth::class, 'logout'])->name('customer.logout')->middleware($customerMiddleware);;

	Route::prefix('sliders')->group(function () use ($customerMiddleware){
		Route::get('/', [CustomerSlidersController::class, 'index'])->name('customer.sliders.index');
	});

	Route::prefix('trending')->group(function (){
		Route::get('/picks', [TrendController::class, 'index'])->name('customer.trending.picks');
	});
});