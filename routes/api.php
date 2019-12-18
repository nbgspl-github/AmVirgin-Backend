<?php

use App\Http\Controllers\App\Customer\AuthController as CustomerAuth;
use App\Http\Controllers\App\Customer\TwoFactorAuthController as CustomerTwoFactorAuth;
use App\Http\Controllers\App\Customer\SlidersController as CustomerSlidersController;
use App\Http\Controllers\App\Seller\AttributesController as SellerAttributesController;
use App\Http\Controllers\App\Seller\AuthController as SellerAuthController;
use App\Http\Controllers\App\Seller\CategoriesController as SellerCategoriesController;
use App\Http\Controllers\App\Seller\ProductsController as SellerProductsController;
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
	Route::get('/', [SellerAuthController::class, 'exists'])->name('seller.check');
	Route::get('/profile', [SellerAuthController::class, 'profile'])->name('seller.profile')->middleware($sellerMiddleware);
	Route::post('/login', [SellerAuthController::class, 'login'])->name('seller.login');
	Route::post('/register', [SellerAuthController::class, 'register'])->name('seller.register');
	Route::post('/logout', [SellerAuthController::class, 'logout'])->name('seller.logout')->middleware($sellerMiddleware);

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
	Route::get('/', [CustomerAuth::class, 'exists'])->name('customer.check');
	Route::get('/profile', [CustomerAuth::class, 'profile'])->name('customer.profile')->middleware($customerMiddleware);
	Route::post('/login/password', [CustomerAuth::class, 'login'])->name('customer.login.regular');
	Route::post('/login/2factor', [CustomerTwoFactorAuth::class, 'login'])->name('customer.login.twofactor');
	Route::get('/register', [CustomerTwoFactorAuth::class, 'preRegister'])->name('customer.register.begin');
	Route::post('/register', [CustomerTwoFactorAuth::class, 'postRegister'])->name('customer.register.end');
	Route::post('/logout', [CustomerAuth::class, 'logout'])->name('customer.logout')->middleware($customerMiddleware);;

	Route::prefix('sliders')->group(function () use ($customerMiddleware){
		Route::get('/', [CustomerSlidersController::class, 'index'])->name('customer.sliders.index');
	});
});