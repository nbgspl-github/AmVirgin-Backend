<?php

use App\Http\Controllers\App\Seller\Attributes\AttributeListController;
use App\Http\Controllers\App\Seller\Attributes\AttributeValuesController;
use App\Http\Controllers\App\Seller\AttributesController;
use App\Http\Controllers\App\Seller\AuthController;
use App\Http\Controllers\App\Seller\CategoriesController;
use App\Http\Controllers\App\Seller\CountriesController;
use App\Http\Controllers\App\Seller\CurrenciesController;
use App\Http\Controllers\App\Seller\ProductImagesController;
use App\Http\Controllers\App\Seller\ProductsController;
use App\Http\Controllers\App\Seller\TwoFactorAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TwoFactorAuthController::class, 'exists'])->name('seller.check');
Route::get('/profile', [AuthController::class, 'profile'])->name('seller.profile')->middleware('auth:seller-api');
Route::post('/login', [TwoFactorAuthController::class, 'login'])->name('seller.login');
Route::post('/register', [TwoFactorAuthController::class, 'register'])->name('seller.register');
Route::post('/logout', [AuthController::class, 'logout'])->name('seller.logout')->middleware('auth:seller-api');
Route::post('/profile', [AuthController::class, 'profile'])->name('seller.logout')->middleware('auth:seller-api');

Route::prefix('categories')->group(function () {
	Route::get('/', [CategoriesController::class, 'index'])->name('seller.categories.index');
	Route::get('/{id}/attributes', [AttributeListController::class, 'show'])->name('seller.categories.attributes.index');
	Route::post('/{id}/attributes', [AttributeListController::class, 'store'])->name('seller.categories.attributes.store');
});

Route::prefix('attributes')->group(function () {
	Route::get('/{attributeId}/values', [AttributeValuesController::class, 'show']);
});

Route::middleware('auth:seller-api')->prefix('products')->group(function () {
	Route::get('/', [ProductsController::class, 'index'])->name('seller.products.index');
	Route::post('/', [ProductsController::class, 'store'])->name('seller.products.store');
	Route::get('{id}', [ProductsController::class, 'show'])->name('seller.products.show');
	Route::get('edit/{id}', [ProductsController::class, 'edit'])->name('seller.products.edit');
	Route::post('{id}', [ProductsController::class, 'update'])->name('seller.products.update');
	Route::delete('{id}', [ProductsController::class, 'delete'])->name('seller.products.delete');
	Route::delete('/images/{id}', [ProductImagesController::class, 'delete'])->name('seller.products.images.delete');
});

Route::prefix('currencies')->group(function () {
	Route::get('/', [CurrenciesController::class, 'index'])->name('seller.currencies.index');
});

Route::prefix('countries')->group(function () {
	Route::get('/', [CountriesController::class, 'index'])->name('seller.countries.index');
});