<?php

use App\Http\Controllers\App\UserController;
use App\Http\Resources\UserResource;
use App\Interfaces\Methods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('abc', function (Request $request) {
//		return new UserResource($request->user());
	return "Hey";
})->middleware('auth:api');

/**
 * Protected User Routes [Requires Authenticated User]
 */
Route::prefix('user')->middleware('auth:api')->group(function () {
//	Route::get('/', function (Request $request) {
////		return new UserResource($request->user());
//		return "Hey";
//	});
	Route::put('/{id}', [UserController::class, Methods::Update]);
});

/**
 * Unprotected User Routes
 */
Route::prefix('user')->group(function () {
	Route::get('/{id}', [UserController::class, Methods::Index]);
	Route::post('/{id}', [UserController::class, Methods::Store]);
});