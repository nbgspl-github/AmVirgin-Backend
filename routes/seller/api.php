<?php

use App\Classes\Str;
use App\Http\Controllers\App\Seller\Attributes\AttributeListController;
use App\Http\Controllers\App\Seller\Attributes\AttributeValuesController;
use App\Http\Controllers\App\Seller\Attributes\ProductsAttributesController;
use App\Http\Controllers\App\Seller\AuthController;
use App\Http\Controllers\App\Seller\CategoriesController;
use App\Http\Controllers\App\Seller\CitiesController;
use App\Http\Controllers\App\Seller\CountriesController;
use App\Http\Controllers\App\Seller\CurrenciesController;
use App\Http\Controllers\App\Seller\HsnCodeController;
use App\Http\Controllers\App\Seller\ProductImagesController;
use App\Http\Controllers\App\Seller\ProductsController;
use App\Http\Controllers\App\Seller\StatesController;
use App\Http\Controllers\App\Seller\TwoFactorAuthController;
use App\Http\Controllers\App\Seller\OrdersController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TwoFactorAuthController::class, 'exists'])->name('seller.check');
Route::get('/profile', [AuthController::class, 'profile'])->name('seller.profile')->middleware('auth:seller-api');
Route::post('/login', [TwoFactorAuthController::class, 'login'])->name('seller.login');
Route::post('/register', [TwoFactorAuthController::class, 'register'])->name('seller.register');
Route::post('/logout', [AuthController::class, 'logout'])->name('seller.logout')->middleware('auth:seller-api');
Route::post('/profile', [AuthController::class, 'profile'])->name('seller.logout')->middleware('auth:seller-api');
Route::post('/profile/avatar', [AuthController::class, 'updateAvatar'])->name('seller.update.avatar')->middleware('auth:seller-api');
Route::put('/profile', [AuthController::class, 'updateProfile'])->name('seller.update.profile')->middleware('auth:seller-api');

Route::post('/change-password', [AuthController::class, 'changePassword'])->name('seller.changePassword')->middleware('auth:seller-api');

Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('seller.forgotPassword');

Route::post('/reset-password-token', [AuthController::class, 'getResetPasswordToken'])->name('seller.getResetPasswordToken');

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
	Route::delete('/attributes/{id}', [ProductsAttributesController::class, 'delete'])->name('seller.products.attributes.delete');
});

Route::prefix('currencies')->group(function () {
	Route::get('/', [CurrenciesController::class, 'index'])->name('seller.currencies.index');
});

Route::prefix('countries')->group(function () {
	Route::get('/', [CountriesController::class, 'index'])->name('seller.countries.index');
	Route::get('{countryId}/states', [StatesController::class, 'index'])->name('seller.states.index');
	Route::get('states/{stateId}/cities', [CitiesController::class, 'index'])->name('seller.states.index');
});

Route::prefix('orders')->middleware('auth:seller-api')->group(function () {
	Route::get(Str::Empty, [OrdersController::class, 'index']);
	Route::get('/{param}', [OrdersController::class, 'getOrdersDetails']);
	Route::get('/{id}/{status}', [OrdersController::class, 'updateOrderStatus']); 
});
Route::prefix('order')->middleware('auth:seller-api')->group(function () {  
	Route::get('/status', [OrdersController::class, 'getOrderStatus']);
});

Route::prefix('orders-by-status')->middleware('auth:seller-api')->group(function () {  
	Route::get('/{param}', [OrdersController::class, 'getOrderByStatus']);
});

Route::prefix('customer')->middleware('auth:seller-api')->group(function () {
	Route::get('{param}', [OrdersController::class, 'customer']);
});

Route::prefix('hsn')->group(function () {
	Route::get('/', [HsnCodeController::class, 'index']);
});