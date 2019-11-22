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
use App\Http\Controllers\Web\CategoriesController;
use App\Http\Controllers\Web\MoviesController;
use App\Http\Controllers\Web\GenresController;

// Authentication Routes
Auth::routes();
Auth::routes(['register' => false]);

// Dashboard Routes
Route::get('/', [DashboardController::class, Methods::Index])->name('home')->middleware('auth');

// User's Routes
Route::get('users', [UserController::class, Methods::Index])->name('users.all')->middleware('auth');
Route::get('users/{id}', [UserController::class, Methods::Index])->name('users.single')->middleware('auth');
Route::get('user/create', [UserController::class, Methods::Create])->name('users.new')->middleware('auth');
Route::post('user', [UserController::class, Methods::Store])->name('users.save')->middleware('auth');
Route::put('user/{id}', [UserController::class, Methods::Update])->name('users.update')->middleware('auth');

// Categories Routes
Route::get('categories', [CategoriesController::class, Methods::Index])->name('categories.all')->middleware('auth');
Route::get('category/create', [CategoriesController::class, Methods::Create])->name('categories.new')->middleware('auth');
Route::post('category/store', [CategoriesController::class, Methods::Store])->name('categories.save')->middleware('auth');

// Movies Routes
Route::get('movies', [MoviesController::class, Methods::Index])->name('movies.all')->middleware('auth');

// Genres Routes
Route::get('genres', [GenresController::class, Methods::Index])->name('genres.all')->middleware('auth');
Route::get('genres/{id}', [GenresController::class, Methods::Index])->name('genres.single')->middleware('auth');
Route::get('genre/create', [GenresController::class, Methods::Create])->name('genres.new')->middleware('auth');
Route::post('genre', [GenresController::class, Methods::Store])->name('genres.save')->middleware('auth');
Route::put('genre/status', [GenresController::class, Methods::UpdateStatus])->name('genres.update.status')->middleware('auth');