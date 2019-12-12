<?php

use App\Http\Controllers\App\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\App\Seller\AttributesController as SellerAttributesController;
use App\Http\Controllers\App\Seller\AuthController as SellerAuthController;
use App\Http\Controllers\App\Seller\CategoriesController as SellerCategoriesController;
use App\Http\Controllers\App\Seller\ProductsController as SellerProductsController;
use Illuminate\Support\Facades\Route;

/**
 * | Global collection of middlewares, add more using pipeline character.
 */
$authMiddleware = 'auth:seller-api';

/**
 * | Set this to false to clear all middleware bindings.
 */
$useAuthMiddleware = true;

if (!$useAuthMiddleware) {
	$authMiddleware = [];
}

Route::prefix('seller')->group(function () use ($authMiddleware){
	Route::get('/', [SellerAuthController::class, 'exists'])->name('seller.check');
	Route::get('/profile', [SellerAuthController::class, 'profile'])->name('seller.profile')->middleware($authMiddleware);
	Route::post('/login', [SellerAuthController::class, 'login'])->name('seller.login');
	Route::post('/register', [SellerAuthController::class, 'register'])->name('seller.register');
	Route::post('/logout', [SellerAuthController::class, 'logout'])->name('seller.logout')->middleware($authMiddleware);

	Route::middleware($authMiddleware)->prefix('categories')->group(function (){
		Route::get('/', [SellerCategoriesController::class, 'index'])->name('seller.categories.index');
		Route::get('/{id}/attributes', [SellerAttributesController::class, 'indexFiltered'])->name('seller.attributes.index');
		Route::post('/{id}/attributes', [SellerAttributesController::class, 'store'])->name('seller.attributes.store');
	});

	Route::middleware($authMiddleware)->prefix('products')->group(function (){
		Route::get('/', [SellerProductsController::class, 'index'])->name('seller.products.index');
	});

	Route::middleware($authMiddleware)->prefix('attributes')->group(function (){
		Route::get('/{id}', [SellerAttributesController::class, 'show'])->name('seller.attributes.show');
	});
});

Route::prefix('customer')->group(function (){
	Route::get('/', [CustomerAuthController::class, 'exists'])->name('customer.check');
	Route::get('/profile', [CustomerAuthController::class, 'profile'])->name('customer.profile')->middleware('auth:customer-api');
	Route::post('/login', [CustomerAuthController::class, 'login'])->name('customer.login');
	Route::post('/register', [CustomerAuthController::class, 'register'])->name('customer.register');
	Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout')->middleware('auth:customer-api');;
});