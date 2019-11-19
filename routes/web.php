<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\DashboardController;
use App\Interfaces\Methods;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\CategoriesController;

// Authentication Routes
Auth::routes();
Auth::routes(['register' => false]);
Route::get('/', [DashboardController::class, Methods::Index])->name('home')->middleware('auth');
Route::get('/users', [UserController::class, Methods::Index])->name('users.all')->middleware('auth');
Route::get('/users/{id}', [UserController::class, Methods::Index])->name('user.single')->middleware('auth');
Route::get('/users/create', [UserController::class, Methods::Create])->name('users.forms.add')->middleware('auth');
Route::post('/users', [UserController::class, Methods::Store])->name('users.add')->middleware('auth');
Route::put('/users/{id}', [UserController::class, Methods::Update])->name('users.update')->middleware('auth');

Route::get('/categories', [CategoriesController::class, Methods::Index])->name('categories.all')->middleware('auth');
Route::get('/categories/create', [CategoriesController::class, Methods::Create])->name('categories.forms.add')->middleware('auth');
Route::post('/categories/store', [CategoriesController::class, Methods::Store])->name('categories.add')->middleware('auth');