<?php

use App\Category;
use App\Classes\Methods;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\Admin\CategoriesController;
use App\Http\Controllers\Web\Admin\CustomerController;
use App\Http\Controllers\Web\Admin\GenresController;
use App\Http\Controllers\Web\Admin\HomeController;
use App\Http\Controllers\Web\Admin\NotificationsController;
use App\Http\Controllers\Web\Admin\SellerController;
use App\Http\Controllers\Web\Admin\ServersController;
use App\Http\Controllers\Web\Admin\SettingsController;
use App\Http\Controllers\Web\Admin\SlidersController;
use App\Http\Controllers\Web\Admin\SubscriptionPlansController;
use App\Http\Controllers\Web\Admin\TvSeriesController;
use App\Http\Controllers\Web\Admin\VideosController;
use App\Models\Genre;
use App\Models\Slider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::redirect('/', '/admin');

/**
 * |--------------------------------------------------------------------------
 * | Admin Authentication & Management Routes
 * |--------------------------------------------------------------------------
 */
Route::prefix('admin')->group(function (){
	Route::get('/', [HomeController::class, Methods::Index])->middleware('auth:admin')->name('admin.home');
	Route::get('/login', [LoginController::class, Methods::auth()::LoginForm])->name('admin.login');
	Route::post('/login', [LoginController::class, Methods::auth()::Login])->name('admin.login.submit');
	Route::post('/logout', [LoginController::class, Methods::auth()::Logout])->name('admin.logout');
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

		// Seller's Route(s)
		Route::prefix('sellers')->middleware('auth:admin')->group(function (){
			Route::get('', [SellerController::class, Methods::Index])->name('admin.sellers.index');
			Route::get('create', [SellerController::class, Methods::Create])->name('admin.sellers.create');
			Route::get('{id}/edit', [SellerController::class, Methods::Edit])->name('admin.sellers.edit');
			Route::get('{id}', [SellerController::class, Methods::Show])->name('admin.sellers.show');
			Route::post('', [SellerController::class, Methods::Store])->name('admin.sellers.store');
			Route::post('{id}', [SellerController::class, Methods::Update])->name('admin.sellers.update');
			Route::put('{id}/status', [SellerController::class, Methods::UpdateStatus])->name('admin.sellers.update.status');
			Route::delete('{id}', [SellerController::class, Methods::Delete])->name('admin.sellers.delete');
		});

		// Categories Route(s)
		Route::prefix('categories')->middleware('auth:admin')->group(function (){
			Route::get('', [CategoriesController::class, Methods::Index])->name('admin.categories.index');
			Route::get('create', [CategoriesController::class, Methods::Create])->name('admin.categories.create');
			Route::get('{id}/edit', [CategoriesController::class, Methods::Edit])->name('admin.categories.edit');
			Route::post('store', [CategoriesController::class, Methods::Store])->name('admin.categories.store');
			Route::post('{id}', [CategoriesController::class, Methods::Update])->name('admin.categories.update');
			Route::delete('{id}', [CategoriesController::class, Methods::Delete])->name('admin.categories.delete');
		});

		// Videos Route(s)
		Route::prefix('videos')->middleware('auth:admin')->group(function (){
			Route::get('', [VideosController::class, Methods::Index])->name('admin.videos.index');
			Route::get('create', [VideosController::class, Methods::Create])->name('admin.videos.create');
			Route::get('/{slug}', [VideosController::class, Methods::Show])->name('admin.videos.show');
			Route::get('{id}/edit/attributes', [VideosController::class, Methods::Edit])->name('admin.videos.edit.attributes')->defaults('type', 'attributes');
			Route::get('{id}/edit/content', [VideosController::class, Methods::Edit])->name('admin.videos.edit.content')->defaults('type', 'content');
			Route::post('store', [VideosController::class, Methods::Store])->name('admin.videos.store');
			Route::post('{id}/attributes', [VideosController::class, Methods::Update])->name('admin.videos.update.attributes')->defaults('type', 'attributes');
			Route::post('{id}/content', [VideosController::class, Methods::Update])->name('admin.videos.update.content')->defaults('type', 'content');
			Route::delete('{id}', [VideosController::class, Methods::Delete])->name('admin.videos.delete');
		});

		// VideoSeries Route(s)
		Route::prefix('tv-series')->middleware('auth:admin')->group(function (){
			Route::get('', [TvSeriesController::class, Methods::Index])->name('admin.tv-series.index');
			Route::get('create', [TvSeriesController::class, Methods::Create])->name('admin.tv-series.create');
			Route::get('/{slug}', [TvSeriesController::class, Methods::Show])->name('admin.tv-series.show');
			Route::get('{id}/edit/attributes', [TvSeriesController::class, Methods::Edit])->name('admin.tv-series.edit.attributes')->defaults('type', 'attributes');
			Route::get('{id}/edit/content', [TvSeriesController::class, Methods::Edit])->name('admin.tv-series.edit.content')->defaults('type', 'content');
			Route::post('store', [TvSeriesController::class, Methods::Store])->name('admin.tv-series.store');
			Route::post('{id}/attributes', [TvSeriesController::class, Methods::Update])->name('admin.tv-series.update.attributes')->defaults('type', 'attributes');
			Route::post('{id}/content', [TvSeriesController::class, Methods::Update])->name('admin.tv-series.update.content')->defaults('type', 'content');
			Route::delete('{id}', [TvSeriesController::class, Methods::Delete])->name('admin.tv-series.delete');
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

		// Images Route(s)
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

		// Servers' Route(s)
		Route::prefix('servers')->middleware('auth:admin')->group(function (){
			Route::get('', [ServersController::class, Methods::Index])->name('admin.servers.index');
			Route::get('create', [ServersController::class, Methods::Create])->name('admin.servers.create');
			Route::get('{id}/edit', [ServersController::class, Methods::Edit])->name('admin.servers.edit');
			Route::get('{id}', [ServersController::class, Methods::Show])->name('admin.servers.show');
			Route::post('', [ServersController::class, Methods::Store])->name('admin.servers.store');
			Route::post('{id}', [ServersController::class, Methods::Update])->name('admin.servers.update');
			Route::put('{id}/status', [ServersController::class, Methods::UpdateStatus])->name('admin.servers.update.status');
			Route::delete('{id}', [ServersController::class, Methods::Delete])->name('admin.servers.delete');
		});

		// Web-series Route(s)
		Route::prefix('series')->middleware('auth:admin')->group(function (){
			Route::get('', [TvSeriesController::class, Methods::Index])->name('admin.series.index');
		});

		// Live TV Route(s)
		Route::prefix('live-tv')->middleware('auth:admin')->group(function (){
			Route::get('', [TvSeriesController::class, Methods::Index])->name('admin.live-tv.index');
		});

		// Subscription Plan Route(s)
		Route::prefix('subscription-plans')->middleware('auth:admin')->group(function (){
			Route::get('', [SubscriptionPlansController::class, Methods::Index])->name('admin.subscription-plans.index');
			Route::get('create', [SubscriptionPlansController::class, Methods::Create])->name('admin.subscription-plans.create');
			Route::get('{id}/edit', [SubscriptionPlansController::class, Methods::Edit])->name('admin.subscription-plans.edit');
			Route::get('{id}', [SubscriptionPlansController::class, Methods::Show])->name('admin.subscription-plans.show');
			Route::post('', [SubscriptionPlansController::class, Methods::Store])->name('admin.subscription-plans.store');
			Route::post('{id}', [SubscriptionPlansController::class, Methods::Update])->name('admin.subscription-plans.update');
			Route::put('{id}/status', [SubscriptionPlansController::class, Methods::UpdateStatus])->name('admin.subscription-plans.update.status');
			Route::delete('{id}', [SubscriptionPlansController::class, Methods::Delete])->name('admin.subscription-plans.delete');
		});

		// Settings Route(s)
		Route::prefix('settings')->middleware('auth:admin')->group(function (){
			Route::get('', [SettingsController::class, Methods::Index])->name('admin.settings.index');
		});

	});
});