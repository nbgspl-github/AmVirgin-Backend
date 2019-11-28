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

use App\Category;
use App\Http\Controllers\Web\NotificationsController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\DashboardController;
use App\Interfaces\Methods;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\CategoriesController;
use App\Http\Controllers\Web\MoviesController;
use App\Http\Controllers\Web\GenresController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Auth\SellerLoginController;

// Admin Authentication Route(s)
Auth::routes();
Auth::routes(['register' => false]);

// Seller Authentication Route(s)
Route::get('seller/login', [SellerLoginController::class, Methods::ShowLoginForm])->name('seller.loginform');
Route::post('seller/login', [SellerLoginController::class, Methods::Login])->name('seller.login');
Route::post('seller/logout', [SellerLoginController::class, Methods::Logout])->name('seller.logout');
Route::get('seller', [DashboardController::class, Methods::Seller])->middleware('auth');

/**
 * |---------------------------------------------------------------
 * | Assign auth middleware to resource routes
 * |---------------------------------------------------------------
 */
Route::middleware('auth')->group(function () {

	// Dashboard Route(s)
	Route::get('/', [DashboardController::class, Methods::Index])->name('home');

	// User's Route(s)
	Route::get('users', [UserController::class, Methods::Index])->name('users.all');
	Route::get('users/{id}', [UserController::class, Methods::Index])->name('users.single');
	Route::get('user/create', [UserController::class, Methods::Create])->name('users.new');
	Route::post('user', [UserController::class, Methods::Store])->name('users.save');
	Route::put('user/{id}', [UserController::class, Methods::Update])->name('users.update');

	// Categories Route(s)
	Route::prefix('categories')->group(function () {
		Route::get('', [CategoriesController::class, Methods::Index])->name('categories.index');
		Route::get('create', [CategoriesController::class, Methods::Create])->name('categories.create');
		Route::get('{id}/edit', [CategoriesController::class, Methods::Edit])->name('categories.edit');
		Route::post('store', [CategoriesController::class, Methods::Store])->name('categories.store');
		Route::post('{id}', [CategoriesController::class, Methods::Update])->name('categories.update');
	});

	// Movies Route(s)
	Route::get('movies', [MoviesController::class, Methods::Index])->name('movies.all');

	// Genres Routes
	Route::prefix('genres')->group(function () {
		Route::get('', [GenresController::class, Methods::Index])->name('genres.index');
		Route::get('create', [GenresController::class, Methods::Create])->name('genres.create');
		Route::get('{id}/edit', [GenresController::class, Methods::Edit])->name('genres.edit');
		Route::get('{id}', [GenresController::class, Methods::Show])->name('genres.show');
		Route::post('', [GenresController::class, Methods::Store])->name('genres.store');
		Route::post('{id}', [GenresController::class, Methods::Update])->name('genres.update');
		Route::put('{id}/status', [GenresController::class, Methods::UpdateStatus])->name('genres.update.status');
		Route::delete('{id}', [GenresController::class, Methods::Delete])->name('genres.delete');
	});

	// Notifications Route(s)
	Route::get('notifications/create', [NotificationsController::class, Methods::Create])->name('notifications.create');
	Route::post('notifications/send', [NotificationsController::class, Methods::Send])->name('notifications.send');

	// Images Routes
	Route::prefix('images')->group(function () {

		// Genre Poster
		Route::get('genre/poster/{id}', function ($id) {
			$genre = Genre::find($id);
			if ($genre != null) {
				return Storage::download($genre->getPoster());
			} else {
				return null;
			}
		})->name('images.genre.poster');

		// Category Poster
		Route::get('category/poster/{id}', function ($id) {
			$category = Category::find($id);
			if ($category != null) {
				return Storage::download($category->getPoster());
			} else {
				return null;
			}
		})->name('images.category.poster');
	});
});


/*<--Route Terminology & Schema-->*/
/**
 * -----------------------------------------------------------------------------------
 * [Index]
 * Description -> Returns a list of all resources for a particular type
 * Verb -> plural
 * Action -> index
 * Name -> resources.index
 * Method -> GET
 * Example -> GET(resources)
 * -----------------------------------------------------------------------------------
 */
/**
 * -----------------------------------------------------------------------------------
 * [Create]
 * Description -> Returns a blank form with data to filled for a particular resource schema
 * Verb -> plural
 * Action -> create
 * Name -> resources.create
 * Method -> GET
 * Example -> GET(resources/create)
 * -----------------------------------------------------------------------------------
 */
/**
 * -----------------------------------------------------------------------------------
 * [Edit]
 * Description -> Returns a form with the data filled for resource for specified Id
 * Verb -> plural
 * Action -> edit
 * Name -> resources.edit
 * Method -> GET
 * Example -> GET(resources/{id})
 * -----------------------------------------------------------------------------------
 */
/**
 * -----------------------------------------------------------------------------------
 * [Show]
 * Description -> Displays a read-only form for a resource specified by Id
 * Verb -> singular
 * Action -> show
 * Name -> resources.show
 * Method -> GET
 * Example -> GET(resource/{id})
 * -----------------------------------------------------------------------------------
 */
/**
 * -----------------------------------------------------------------------------------
 * [Store]
 * Description -> Displays a read-only form for a resource specified by Id
 * Verb -> plural
 * Action -> store
 * Name -> resources.store
 * Method -> POST
 * Example -> POST(resources)
 * -----------------------------------------------------------------------------------
 */
/**
 * -----------------------------------------------------------------------------------
 * [Update]
 * Description -> Updates a resource specified by Id
 * Verb -> plural
 * Action -> update
 * Name -> resources.update
 * Method -> POST
 * Example -> POST(resources/{id})
 * -----------------------------------------------------------------------------------
 */
/**
 * -----------------------------------------------------------------------------------
 * [Update Specific Parameter]
 * Description -> Updates a specific parameter of resource specified by Id and Type
 * Verb -> plural
 * Action -> update or update{parameterName}
 * Name -> resources.update.parameterName
 * Method -> POST/PUT
 * Example -> POST/PUT(resources/{id}/parameterName)
 * -----------------------------------------------------------------------------------
 */
/**
 * -----------------------------------------------------------------------------------
 * [Delete]
 * Description -> Deletes a resource specified by Id
 * Verb -> plural
 * Action -> delete
 * Name -> resources.delete
 * Method -> POST/DELETE
 * Example -> POST/DELETE(resources/delete/{id})
 * -----------------------------------------------------------------------------------
 */
