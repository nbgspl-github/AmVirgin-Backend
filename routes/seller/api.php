<?php

use App\Http\Controllers\App\Seller\AttributesController as SellerAttributesController;
use App\Http\Controllers\App\Seller\AuthController as SellerAuth;
use App\Http\Controllers\App\Seller\CategoriesController as SellerCategoriesController;
use App\Http\Controllers\App\Seller\CountriesController;
use App\Http\Controllers\App\Seller\CurrenciesController;
use App\Http\Controllers\App\Seller\ProductsController as SellerProductsController;
use App\Http\Controllers\App\Seller\TwoFactorAuthController as Seller2FAuth;
use Illuminate\Support\Facades\Route;

Route::prefix('seller')->group(function (){
	Route::get('/', [Seller2FAuth::class, 'exists'])->name('seller.check');
	Route::get('/profile', [SellerAuth::class, 'profile'])->name('seller.profile')->middleware('auth:seller-api');
	Route::post('/login', [Seller2FAuth::class, 'login'])->name('seller.login');
	Route::post('/register', [Seller2FAuth::class, 'register'])->name('seller.register');
	Route::post('/logout', [SellerAuth::class, 'logout'])->name('seller.logout')->middleware('auth:seller-api');
	Route::post('/profile', [SellerAuth::class, 'profile'])->name('seller.logout')->middleware('auth:seller-api');

	Route::prefix('categories')->group(function (){
		Route::get('/', [SellerCategoriesController::class, 'index'])->name('seller.categories.index');
		Route::get('/{id}/attributes', [SellerAttributesController::class, 'indexFiltered'])->name('seller.attributes.index');
		Route::post('/{id}/attributes', [SellerAttributesController::class, 'store'])->name('seller.attributes.store');
	});

	Route::middleware('auth:seller-api')->prefix('products')->group(function (){
		Route::get('/', [SellerProductsController::class, 'index'])->name('seller.products.index');
		Route::post('/', [SellerProductsController::class, 'store']);
		Route::get('/{slug}', [SellerProductsController::class, 'single']);
		Route::post('/edit/{id}', [SellerProductsController::class, 'edit']);
		Route::post('/update/{id}', [SellerProductsController::class, 'update']);
		Route::post('/delete/{id}', [SellerProductsController::class, 'delete']);
	});

	Route::middleware('auth:seller-api')->prefix('attributes')->group(function (){
		Route::get('/{id}', [SellerAttributesController::class, 'show'])->name('seller.attributes.show');
	});

	Route::prefix('currencies')->group(function (){
		Route::get('/', [CurrenciesController::class, 'index'])->name('seller.currencies.index');
	});

	Route::prefix('countries')->group(function (){
		Route::get('/', [CountriesController::class, 'index'])->name('seller.countries.index');
	});
});