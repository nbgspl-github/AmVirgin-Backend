<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\app\Seller\SellerController;
use App\Http\Controllers\App\Seller\Auth\AuthController as SellerAuthController;
use App\Http\Controllers\App\Customer\Auth\AuthController as CustomerAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('seller')->group(function () {
	Route::post('/login', [SellerAuthController::class, 'login'])->name('seller.login');
	Route::post('/logout', [SellerAuthController::class, 'logout'])->name('seller.logout');
});

Route::prefix('customer')->group(function () {
	Route::post('/login', [CustomerAuthController::class, 'login'])->name('customer.login');
	Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');
});

Route::get('/categories', [SellerController::class, 'categories']);
Route::get('/products', [SellerController::class, 'products']);
Route::post('/edit_product', [SellerController::class, 'product_edit']);
Route::post('/delete_product', [SellerController::class, 'product_delete']);
Route::post('/add_product', [SellerController::class, 'add_product']);