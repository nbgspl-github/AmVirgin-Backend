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
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\CategoriesController;
use App\Http\Controllers\Web\MoviesController;
use App\Http\Controllers\Web\GenresController;
use Illuminate\Support\Facades\Storage;

// Authentication Routes
Auth::routes();
Auth::routes(['register' => false]);

Route::get('temp/{id}', function ($id) {
	if ($id == 369) {
		return response()->json([
			'code' => 'BINGO',
		], 200);
	} else {
		return response()->json([
			'code' => null,
		], 404);
	}
});

// Assign auth middleware to resource routes
Route::middleware('auth')->group(function () {

	// Dashboard Routes
	Route::get('/', [DashboardController::class, Methods::Index])->name('home');

	// User's Routes
	Route::get('users', [UserController::class, Methods::Index])->name('users.all');
	Route::get('users/{id}', [UserController::class, Methods::Index])->name('users.single');
	Route::get('user/create', [UserController::class, Methods::Create])->name('users.new');
	Route::post('user', [UserController::class, Methods::Store])->name('users.save');
	Route::put('user/{id}', [UserController::class, Methods::Update])->name('users.update');

	// Categories Routes
	Route::get('categories', [CategoriesController::class, Methods::Index])->name('categories.all');
	Route::get('category/create', [CategoriesController::class, Methods::Create])->name('categories.new');
	Route::post('category/store', [CategoriesController::class, Methods::Store])->name('categories.save');

	// Movies Routes
	Route::get('movies', [MoviesController::class, Methods::Index])->name('movies.all');

	// Genres Routes
	Route::get('genres', [GenresController::class, Methods::Index])->name('genres.index');
	Route::get('genres/create', [GenresController::class, Methods::Create])->name('genres.create');
	Route::get('genres/{id}', [GenresController::class, Methods::Edit])->name('genres.edit');
	Route::get('genre/{id}', [GenresController::class, Methods::Show])->name('genres.show');
	Route::post('genres', [GenresController::class, Methods::Store])->name('genres.store');
	Route::post('genres/{id}', [GenresController::class, Methods::Update])->name('genres.update');
	Route::put('genres/{id}/status', [GenresController::class, Methods::UpdateStatus])->name('genres.update.status');
	Route::delete('genres/{id}', [GenresController::class, Methods::Delete])->name('genres.delete');

	// Images Routes
	Route::prefix('images')->group(function () {

		// Genre Poster
		Route::get('genres/poster/{id}', function ($id) {
			$genre = Genre::find($id);
			if ($genre != null) {
				return Storage::download($genre->getPoster());
			} else {
				return null;
			}
		})->name('images.genre.poster');
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
