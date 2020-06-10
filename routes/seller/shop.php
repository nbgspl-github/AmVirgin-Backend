<?php

use App\Http\Controllers\App\Shop\CartController as ShopCartController;
use App\Http\Controllers\App\Shop\CategoryController as ShopCategoryController;
use App\Http\Controllers\App\Shop\ProductsController as ShopProductsController;
use Illuminate\Support\Facades\Route;

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