<?php

use App\Classes\Methods;
use App\Http\Controllers\App\Customer\Playback\TrailerPlayback;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\Admin\CategoriesBanner;
use App\Http\Controllers\Web\Admin\CategoriesController;
use App\Http\Controllers\Web\Admin\CustomerController;
use App\Http\Controllers\Web\Admin\GenresController;
use App\Http\Controllers\Web\Admin\HomeController;
use App\Http\Controllers\Web\Admin\NotificationsController;
use App\Http\Controllers\Web\Admin\Products\DeletedProductsController;
use App\Http\Controllers\Web\Admin\Products\ProductsController;
use App\Http\Controllers\Web\Admin\SellerController;
use App\Http\Controllers\Web\Admin\ServersController;
use App\Http\Controllers\Web\Admin\SettingsController;
use App\Http\Controllers\Web\Admin\SlidersController;
use App\Http\Controllers\Web\Admin\SubscriptionPlansController;
use App\Http\Controllers\Web\Admin\TvSeries\AttributesController;
use App\Http\Controllers\Web\Admin\TvSeries\ContentController;
use App\Http\Controllers\Web\Admin\TvSeries\MediaController;
use App\Http\Controllers\Web\Admin\TvSeries\SnapsController;
use App\Http\Controllers\Web\Admin\TvSeries\TvSeriesBase;
use App\Http\Controllers\Web\Admin\TvSeriesController;
use App\Http\Controllers\Web\Admin\Videos\VideosBase;
use App\Http\Controllers\Web\Admin\VideosController;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Slider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

const SubDomainPrefix = 'admin';

Route::get('/', function (){
	return view('admin.react.container');
});

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

		//Categories Banner Ro
		Route::prefix('categories-banner')->middleware('auth:admin')->group(function (){
			Route::get('', [CategoriesBanner::class, Methods::Index])->name('admin.categories-banner.index');
			Route::get('create', [CategoriesBanner::class, Methods::Create])->name('admin.categories-banner.create');
			Route::get('{id}/edit', [CategoriesBanner::class, Methods::Edit])->name('admin.categories-banner.edit');
			Route::post('store', [CategoriesBanner::class, Methods::Store])->name('admin.categories-banner.store');
			Route::post('{id}', [CategoriesBanner::class, Methods::Update])->name('admin.categories-banner.update');
			Route::delete('{id}', [CategoriesBanner::class, Methods::Delete])->name('admin.categories-banner.delete');

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
			Route::get('', [VideosBase::class, 'index'])->name('admin.videos.index');
			Route::get('actions/{id}', [VideosBase::class, 'choose'])->name('admin.videos.edit.action');
			Route::get('create', [VideosBase::class, 'create'])->name('admin.videos.create');
			Route::get('/{slug}', [VideosBase::class, 'show'])->name('admin.videos.show');
			Route::prefix('edit/{id}')->group(function (){
				Route::get('attributes', [\App\Http\Controllers\Web\Admin\Videos\AttributesController::class, 'edit'])->name('admin.videos.edit.attributes');
				Route::get('content', [\App\Http\Controllers\Web\Admin\Videos\ContentController::class, 'edit'])->name('admin.videos.edit.content');
				Route::get('media', [\App\Http\Controllers\Web\Admin\Videos\MediaController::class, 'edit'])->name('admin.videos.edit.media');
				Route::get('snaps', [\App\Http\Controllers\Web\Admin\Videos\SnapsController::class, 'edit'])->name('admin.videos.edit.snaps');
			});
			Route::prefix('{id}/update')->group(function (){
				Route::post('attributes', [\App\Http\Controllers\Web\Admin\Videos\AttributesController::class, 'update'])->name('admin.videos.update.attributes');
				Route::post('content', [\App\Http\Controllers\Web\Admin\Videos\ContentController::class, 'update'])->name('admin.videos.update.content');
				Route::post('media', [\App\Http\Controllers\Web\Admin\Videos\MediaController::class, 'update'])->name('admin.videos.update.media');
				Route::post('snaps', [\App\Http\Controllers\Web\Admin\Videos\SnapsController::class, 'update'])->name('admin.videos.update.snaps');
			});
			Route::post('store', [VideosBase::class, Methods::Store])->name('admin.videos.store');
			Route::prefix('{id}')->group(function (){
				Route::delete('', [VideosBase::class, 'delete'])->name('admin.videos.delete');
				Route::delete('content/{contentId}', [\App\Http\Controllers\Web\Admin\Videos\ContentController::class, 'delete'])->name('admin.videos.delete.content');
				Route::delete('snaps/{snapId}', [\App\Http\Controllers\Web\Admin\Videos\SnapsController::class, 'delete'])->name('admin.videos.delete.snaps');
			});
		});

		// TV Series Route(s)
		Route::prefix('tv-series')->middleware('auth:admin')->group(function (){
			Route::get('', [TvSeriesBase::class, 'index'])->name('admin.tv-series.index');
			Route::get('actions/{id}', [TvSeriesBase::class, 'choose'])->name('admin.tv-series.edit.action');
			Route::get('create', [TvSeriesBase::class, 'create'])->name('admin.tv-series.create');
			Route::get('/{slug}', [TvSeriesBase::class, 'show'])->name('admin.tv-series.show');
			Route::prefix('edit/{id}')->group(function (){
				Route::get('attributes', [AttributesController::class, 'edit'])->name('admin.tv-series.edit.attributes');
				Route::get('content', [ContentController::class, 'edit'])->name('admin.tv-series.edit.content');
				Route::get('media', [MediaController::class, 'edit'])->name('admin.tv-series.edit.media');
				Route::get('snaps', [SnapsController::class, 'edit'])->name('admin.tv-series.edit.snaps');
			});
			Route::prefix('{id}/update')->group(function (){
				Route::post('attributes', [AttributesController::class, 'update'])->name('admin.tv-series.update.attributes');
				Route::post('content', [ContentController::class, 'update'])->name('admin.tv-series.update.content');
				Route::post('media', [MediaController::class, 'update'])->name('admin.tv-series.update.media');
				Route::post('snaps', [SnapsController::class, 'update'])->name('admin.tv-series.update.snaps');
			});
			Route::post('store', [TvSeriesBase::class, 'store'])->name('admin.tv-series.store');
			Route::prefix('{id}')->group(function (){
				Route::delete('', [TvSeriesBase::class, 'delete'])->name('admin.tv-series.delete');
				Route::delete('content/{contentId}', [ContentController::class, 'delete'])->name('admin.tv-series.delete.content');
				Route::delete('snaps/{snapId}', [SnapsController::class, 'delete'])->name('admin.tv-series.delete.snaps');
			});
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

		// Products Route(s)
		Route::prefix('products')->middleware('auth:admin')->group(function (){
			Route::get('', [ProductsController::class, Methods::Index])->name('admin.products.index');
			Route::get('/deleted', [DeletedProductsController::class, Methods::Index])->name('admin.products.deleted.index');
		});

		// Settings Route(s)
		Route::prefix('settings')->middleware('auth:admin')->group(function (){
			Route::get('', [SettingsController::class, Methods::Index])->name('admin.settings.index');
		});
	});
});