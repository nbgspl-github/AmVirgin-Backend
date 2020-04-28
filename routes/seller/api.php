<?php

use App\Classes\Str;
use App\Http\Controllers\App\Seller\Attributes\ListController;
use App\Http\Controllers\App\Seller\Attributes\ValueController;
use App\Http\Controllers\App\Seller\Attributes\ProductAttributeController;
use App\Http\Controllers\App\Seller\AuthController;
use App\Http\Controllers\App\Seller\CategoryController;
use App\Http\Controllers\App\Seller\CityController;
use App\Http\Controllers\App\Seller\CountryController;
use App\Http\Controllers\App\Seller\CurrencyController;
use App\Http\Controllers\App\Seller\HsnCodeController;
use App\Http\Controllers\App\Seller\ProductImageController;
use App\Http\Controllers\App\Seller\ProductController;
use App\Http\Controllers\App\Seller\StateController;
use App\Http\Controllers\App\Seller\TwoFactorAuthController;
use App\Http\Controllers\App\Seller\OrderController;
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

Route::post('/change-email', [AuthController::class, 'changeEmail'])->name('seller.changeEmail')->middleware('auth:seller-api')->middleware('auth:seller-api');

Route::post('/change-email-token', [AuthController::class, 'getChangeEmailToken'])->name('seller.getChangeEmailToken')->middleware('auth:seller-api');

Route::post('/reset-password-token', [AuthController::class, 'getResetPasswordToken'])->name('seller.getResetPasswordToken');

Route::prefix('categories')->group(function (){
	Route::get('/', [CategoryController::class, 'index'])->name('seller.categories.index');
	Route::get('/{id}/attributes', [ListController::class, 'show'])->name('seller.categories.attributes.index');
});

Route::prefix('attributes')->group(function (){
	Route::get('/{attributeId}/values', [ValueController::class, 'show']);
});

Route::middleware('auth:seller-api')->prefix('products')->group(function (){
	Route::get('/', [ProductController::class, 'index'])->name('seller.products.index');
	Route::post(Str::Empty, [ProductController::class, 'store'])->name('seller.products.store');
	Route::get('{id}', [ProductController::class, 'show'])->name('seller.products.show');
	Route::get('edit/{id}', [ProductController::class, 'edit'])->name('seller.products.edit');
	Route::post('{id}', [ProductController::class, 'update'])->name('seller.products.update');
	Route::delete('{id}', [ProductController::class, 'delete'])->name('seller.products.delete');
	Route::delete('/images/{id}', [ProductImageController::class, 'delete'])->name('seller.products.images.delete');
	Route::delete('/attributes/{id}', [ProductAttributeController::class, 'delete'])->name('seller.products.attributes.delete');

	Route::prefix('token')->group(function (){
		Route::get('create', [ProductController::class, 'token']);
	});

	Route::prefix('trailer')->group(function (){
		Route::post('upload', [\App\Http\Controllers\App\Seller\Products\ProductTrailerController::class, 'store']);
	});
});

Route::prefix('currencies')->group(function (){
	Route::get('/', [CurrencyController::class, 'index'])->name('seller.currencies.index');
});

Route::prefix('countries')->group(function (){
	Route::get('/', [CountryController::class, 'index'])->name('seller.countries.index');
	Route::get('{countryId}/states', [StateController::class, 'index'])->name('seller.states.index');
	Route::get('states/{stateId}/cities', [CityController::class, 'index'])->name('seller.states.index');
});

Route::prefix('orders')->middleware('auth:seller-api')->group(function (){
	Route::get(Str::Empty, [OrderController::class, 'index']);
	Route::get('{id}', [OrderController::class, 'show']);
	Route::get('/{id}/{status}', [OrderController::class, 'updateOrderStatus']);
});
Route::prefix('order')->middleware('auth:seller-api')->group(function (){
	Route::get('/status', [OrderController::class, 'getOrderStatus']);
});

Route::prefix('orders-by-status')->middleware('auth:seller-api')->group(function (){
	Route::get('/{param}', [OrderController::class, 'getOrderByStatus']);
});

Route::prefix('customer')->middleware('auth:seller-api')->group(function (){
	Route::get('{param}', [OrderController::class, 'customer']);
});

Route::prefix('hsn')->group(function (){
	Route::get('/', [HsnCodeController::class, 'index']);
});

Route::prefix('brands')->middleware('auth:seller-api')->group(function (){
	Route::get(Str::Empty, [\App\Http\Controllers\App\Seller\BrandController::class, 'index']);
	Route::get('approved', [\App\Http\Controllers\App\Seller\BrandController::class, 'show']);
	Route::post('approval', [\App\Http\Controllers\App\Seller\BrandController::class, 'store']);
});

Route::prefix('announcements')->group(function (){
	Route::get(Str::Empty, [\App\Http\Controllers\App\Seller\AnnouncementController::class, 'index']);
});