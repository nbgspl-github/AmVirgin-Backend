<?php

use App\Classes\Methods;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\UserController;
use App\Http\Controllers\app\Seller\SellerController;

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

Route::get('/categories', [SellerController::class, 'categories']);
Route::get('/products', [SellerController::class, 'products']);
Route::post('/edit_product', [SellerController::class, 'product_edit']);
Route::post('/delete_product', [SellerController::class, 'product_delete']);
Route::post('/add_product', [SellerController::class, 'add_product']);
