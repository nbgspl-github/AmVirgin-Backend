<?php

use App\Category;
use App\Classes\Methods;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Web\Admin\CategoriesController;
use App\Http\Controllers\Web\Admin\CustomerController;
use App\Http\Controllers\Web\Admin\GenresController;
use App\Http\Controllers\Web\Admin\HomeController as AdminHome;
use App\Http\Controllers\Web\Admin\NotificationsController;
use App\Http\Controllers\Web\Admin\SlidersController;
use App\Http\Controllers\Web\Admin\VideosController;
use App\Http\Controllers\Web\Customer\HomeController as CustomerHome;
use App\Http\Controllers\Web\Seller\HomeController as SellerHome;
use App\Models\Genre;
use App\Models\Slider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/**
 * |------------------------------------------------
 * | Admin Authentication & Management Routes
 * |------------------------------------------------
 */
Route::prefix('admin')->group(function (){
	Route::get('/', [AdminHome::class, Methods::Index])->middleware('auth:admin')->name('admin.home');
	Route::get('/login', [AdminLoginController::class, Methods::auth()::LoginForm])->name('admin.login');
	Route::post('/login', [AdminLoginController::class, Methods::auth()::Login])->name('admin.login.submit');
	Route::post('/logout', [AdminLoginController::class, Methods::auth()::Logout])->name('admin.logout');
	Route::middleware('auth:admin')->group(function (){

		// Customer's Route(s)
		Route::prefix('customers')->middleware('auth:admin')->group(function (){
			Route::get('', [CustomerController::class, Methods::Index])->name('admin.customers.index');
			Route::get('create', [CustomerController::class, Methods::Create])->name('admin.customers.create');
			Route::get('{id}/edit', [CustomerController::class, Methods::Edit])->name('admin.customers.edit');
			Route::get('{id}', [CustomerController::class, Methods::Show])->name('admin.customers.show');
			Route::post('', [CustomerController::class, Methods::Store])->name('admin.customers.store');
			Route::post('{id}', [CustomerController::class, Methods::Update])->name('admin.customers.update');
			Route::put('{id}/status', [CustomerController::class, Methods::UpdateStatus])->name('admin.customers.update.status');
			Route::delete('{id}', [CustomerController::class, Methods::Delete])->name('admin.customers.delete');
		});

		// Categories Route(s)
		Route::prefix('categories')->middleware('auth:admin')->group(function (){
			Route::get('', [CategoriesController::class, Methods::Index])->name('admin.categories.index');
			Route::get('create', [CategoriesController::class, Methods::Create])->name('admin.categories.create');
			Route::get('{id}/edit', [CategoriesController::class, Methods::Edit])->name('admin.categories.edit');
			Route::post('store', [CategoriesController::class, Methods::Store])->name('admin.categories.store');
			Route::post('{id}', [CategoriesController::class, Methods::Update])->name('admin.categories.update');
		});

		// Videos Route(s)
		Route::prefix('videos')->middleware('auth:admin')->group(function (){
			Route::get('', [VideosController::class, Methods::Index])->name('admin.videos.index');
			Route::get('create', [VideosController::class, Methods::Create])->name('admin.videos.create');
			Route::get('{id}/edit', [VideosController::class, Methods::Edit])->name('admin.videos.edit');
			Route::post('store', [VideosController::class, Methods::Store])->name('admin.videos.store');
			Route::post('{id}', [VideosController::class, Methods::Update])->name('admin.videos.update');
		});

		// Genres Route(s)
		Route::prefix('genres')->middleware('auth:admin')->group(function (){
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
		Route::prefix('notifications')->middleware('auth:admin')->group(function (){
			Route::get('create', [NotificationsController::class, Methods::Create])->name('admin.notifications.create');
			Route::post('send', [NotificationsController::class, Methods::Send])->name('admin.notifications.send');
		});

		// Sliders Route(s)
		Route::prefix('sliders')->middleware('auth:admin')->group(function (){
			Route::get('', [SlidersController::class, Methods::Index])->name('admin.sliders.index');
			Route::get('create', [SlidersController::class, Methods::Create])->name('admin.sliders.create');
			Route::get('{id}/edit', [SlidersController::class, Methods::Edit])->name('admin.sliders.edit');
			Route::get('{id}', [SlidersController::class, Methods::Show])->name('admin.sliders.show');
			Route::post('', [SlidersController::class, Methods::Store])->name('admin.sliders.store');
			Route::post('{id}', [SlidersController::class, Methods::Update])->name('admin.sliders.update');
			Route::put('{id}/status', [SlidersController::class, Methods::UpdateStatus])->name('admin.sliders.update.status');
			Route::delete('{id}', [SlidersController::class, Methods::Delete])->name('admin.sliders.delete');
		});

		// Images Routes
		Route::prefix('images')->group(function (){

			// Genre Poster
			Route::get('genre/poster/{id}', function ($id){
				$genre = Genre::find($id);
				if ($genre != null) {
					return Storage::download($genre->getPoster());
				}
				else {
					return null;
				}
			})->name('images.genre.poster');

			// Category Poster
			Route::get('category/poster/{id}', function ($id){
				$category = Category::find($id);
				if ($category != null) {
					return Storage::download($category->getPoster());
				}
				else {
					return null;
				}
			})->name('images.category.poster');

			// Slider Poster
			Route::get('slider/{id}/poster', function ($id){
				$slide = Slider::find($id);
				if ($slide != null) {
					return Storage::download($slide->getPoster());
				}
				else {
					return null;
				}
			})->name('images.slider.poster');
		});
	});
});

/**
 * |------------------------------------------------
 * | Customer Authentication Routes
 * |------------------------------------------------
 */
Route::prefix('')->group(function (){
	Route::get('/', [CustomerHome::class, Methods::Index])->name('customer.home');
	Route::get('/login', [CustomerLoginController::class, Methods::auth()::LoginForm])->name('customer.login');
	Route::post('/login', [CustomerLoginController::class, Methods::auth()::Login])->name('customer.login.submit');
});

/**
 * |------------------------------------------------
 * | Seller Authentication Routes
 * |------------------------------------------------
 */
Route::prefix('seller')->group(function (){
	Route::get('/', [SellerHome::class, Methods::Index])->middleware('auth:seller')->name('seller.home');
	Route::get('/login', [SellerLoginController::class, Methods::auth()::LoginForm])->name('seller.login');
	Route::post('/login', [SellerLoginController::class, Methods::auth()::Login])->name('seller.login.submit');
	Route::post('/logout', [SellerLoginController::class, Methods::auth()::Logout])->name('seller.logout');
});

/**
 * |------------------------------------------------
 * | Common Authentication Routes
 * |------------------------------------------------
 */
Auth::routes();