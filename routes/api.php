<?php

use App\Http\Controllers\App\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\App\Seller\AuthController as SellerAuthController;
use App\Http\Controllers\App\Seller\ProductController as SellerProductsController;
use Illuminate\Support\Facades\Route;

Route::prefix('seller')->group(function (){
	Route::get('/', [SellerAuthController::class, 'exists'])->name('seller.check');
	Route::get('/profile', [SellerAuthController::class, 'profile'])->name('seller.profile')->middleware('auth:seller-api');
	Route::post('/login', [SellerAuthController::class, 'login'])->name('seller.login');
	Route::post('/register', [SellerAuthController::class, 'register'])->name('seller.register');
	Route::post('/logout', [SellerAuthController::class, 'logout'])->name('seller.logout')->middleware('auth:seller-api');

//	Route::middleware('auth:seller-api')->prefix('products')->group(function (){
//		Route::get('', [SellerProductsController::class, 'index'])->name('seller.products.index');
//	});
	Route::get('/products', [SellerProductsController::class, 'index']);
});

Route::prefix('customer')->group(function (){
	Route::get('/', [CustomerAuthController::class, 'exists'])->name('customer.check');
	Route::get('/profile', [CustomerAuthController::class, 'profile'])->name('customer.profile')->middleware('auth:customer-api');
	Route::post('/login', [CustomerAuthController::class, 'login'])->name('customer.login');
	Route::post('/register', [CustomerAuthController::class, 'register'])->name('customer.register');
	Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout')->middleware('auth:customer-api');;
});