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

// Authentication Routes
Auth::routes();
Auth::routes(['register' => false]);
Route::get('/', [DashboardController::class, Methods::Index])->name('home');
Route::get('/users', [UserController::class, Methods::Index])->name('users.all');
Route::get('/users/{id}', [UserController::class, Methods::Index])->name('user.single');