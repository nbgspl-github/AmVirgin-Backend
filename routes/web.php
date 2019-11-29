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
use App\Http\Controllers\Web\CategoriesController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\GenresController;
use App\Http\Controllers\Web\MoviesController;
use App\Http\Controllers\Web\NotificationsController;
use App\Http\Controllers\Web\CustomerController;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\CustomerLoginController;
use App\Http\Controllers\Auth\CustomerRegisterController;
use App\Http\Controllers\Auth\SellerLoginController;
use App\Http\Controllers\Web\Admin\HomeController as AdminHome;
use App\Http\Controllers\Web\Customer\HomeController as CustomerHome;
use App\Http\Controllers\Web\Seller\HomeController as SellerHome;
use App\Classes\Methods;
use Illuminate\Support\Facades\Storage;

/**
 * |------------------------------------------------
 * | Admin Authentication & Management Routes
 * |------------------------------------------------
 */
Route::prefix('admin')->group(function () {
	Route::get('/', [AdminHome::class, Methods::Index])->middleware('auth:admin')->name('admin.home');
	Route::get('/login', [AdminLoginController::class, Methods::auth()::LoginForm])->name('admin.login');
	Route::post('/login', [AdminLoginController::class, Methods::auth()::AttemptLogin])->name('admin.login.submit');
	Route::post('/logout', [AdminLoginController::class, Methods::auth()::Logout])->name('admin.logout');
	Route::middleware('auth:admin')->group(function () {

		// Customer's Route(s)
		Route::prefix('customers')->middleware('auth:admin')->group(function () {
			Route::get('', [CustomerController::class, Methods::Index])->name('admin.customers.index');
			Route::get('create', [CustomerController::class, Methods::Create])->name('admin.customers.create');
			Route::get('{id}/edit', [CustomerController::class, Methods::Edit])->name('admin.customers.edit');
			Route::get('{id}', [CustomerController::class, Methods::Show])->name('admin.customers.show');
			Route::post('', [CustomerController::class, Methods::Store])->name('admin.customers.store');
			Route::post('{id}', [CustomerController::class, Methods::Update])->name('admin.customers.update');
			Route::put('{id}/status', [CustomerController::class, Methods::UpdateStatus])->name('admin.customers.update.status');
			Route::delete('{id}', [CustomerController::class, Methods::Delete])->name('admin.customers.delete');
		});
//		Route::get('customers', [CustomerController::class, Methods::Index])->name('admin.customers.index')->middleware('auth:admin');
//		Route::get('customers/{id}', [CustomerController::class, Methods::Index])->name('admin.customers.edit');
//		Route::get('customer/create', [CustomerController::class, Methods::Create])->name('admin.customers.create');
//		Route::post('customer', [CustomerController::class, Methods::Store])->name('admin.customers.store');
//		Route::put('customers/{id}', [CustomerController::class, Methods::Update])->name('admin.customers.update');

		// Categories Route(s)
		Route::prefix('categories')->middleware('auth:admin')->group(function () {
			Route::get('', [CategoriesController::class, Methods::Index])->name('admin.categories.index');
			Route::get('create', [CategoriesController::class, Methods::Create])->name('admin.categories.create');
			Route::get('{id}/edit', [CategoriesController::class, Methods::Edit])->name('admin.categories.edit');
			Route::post('store', [CategoriesController::class, Methods::Store])->name('admin.categories.store');
			Route::post('{id}', [CategoriesController::class, Methods::Update])->name('admin.categories.update');
		});

		// Movies Route(s)
		Route::get('movies', [MoviesController::class, Methods::Index])->name('admin.movies.all')->middleware('auth:admin');

		// Genres Routes
		Route::prefix('genres')->middleware('auth:admin')->group(function () {
			Route::get('', [GenresController::class, Methods::Index])->name('admin.genres.index');
			Route::get('create', [GenresController::class, Methods::Create])->name('admin.genres.create');
			Route::get('{id}/edit', [GenresController::class, Methods::Edit])->name('admin.genres.edit');
			Route::get('{id}', [GenresController::class, Methods::Show])->name('admin.genres.show');
			Route::post('', [GenresController::class, Methods::Store])->name('admin.genres.store');
			Route::post('{id}', [GenresController::class, Methods::Update])->name('admin.genres.update');
			Route::put('{id}/status', [GenresController::class, Methods::UpdateStatus])->name('admin.genres.update.status');
			Route::delete('{id}', [GenresController::class, Methods::Delete])->name('admin.genres.delete');
		});

		// Notifications Route(s)
		Route::prefix('notifications')->middleware('auth:admin')->group(function () {
			Route::get('create', [NotificationsController::class, Methods::Create])->name('admin.notifications.create');
			Route::post('send', [NotificationsController::class, Methods::Send])->name('admin.notifications.send');
		});

		// Images Routes
		Route::prefix('images')->group(function () {

			// Genre Poster
			Route::get('genre/poster/{id}', function ($id) {
				$genre = Genre::find($id);
				if ($genre != null) {
					return Storage::download($genre->getPoster());
				}
				else {
					return null;
				}
			})->name('images.genre.poster');

			// Category Poster
			Route::get('category/poster/{id}', function ($id) {
				$category = Category::find($id);
				if ($category != null) {
					return Storage::download($category->getPoster());
				}
				else {
					return null;
				}
			})->name('images.category.poster');
		});
	});
});

/**
 * |------------------------------------------------
 * | Customer Authentication Routes
 * |------------------------------------------------
 */
Route::prefix('')->group(function () {
	Route::get('/', [CustomerHome::class, Methods::Index])->name('customer.home');
	Route::get('/login', [CustomerLoginController::class, Methods::auth()::LoginForm])->name('customer.login');
	Route::post('/login', [CustomerLoginController::class, Methods::auth()::AttemptLogin])->name('customer.login.submit');
});

/**
 * |------------------------------------------------
 * | Seller Authentication Routes
 * |------------------------------------------------
 */
Route::prefix('seller')->group(function () {
	Route::get('/', [SellerHome::class, Methods::Index])->middleware('auth:seller')->name('seller.home');
	Route::get('/login', [SellerLoginController::class, Methods::auth()::LoginForm])->name('seller.login');
	Route::post('/login', [SellerLoginController::class, Methods::auth()::AttemptLogin])->name('seller.login.submit');
	Route::post('/logout', [SellerLoginController::class, Methods::auth()::Logout])->name('seller.logout');
});

/**
 * |------------------------------------------------
 * | Common Authentication Routes
 * |------------------------------------------------
 */
Auth::routes();