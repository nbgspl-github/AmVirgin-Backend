<?php

use App\Classes\Methods;
use App\Classes\Str;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\Admin\CategoriesBanner;
use App\Http\Controllers\Web\Admin\CategoryController;
use App\Http\Controllers\Web\Admin\CustomerController;
use App\Http\Controllers\Web\Admin\GenresController;
use App\Http\Controllers\Web\Admin\HomeController;
use App\Http\Controllers\Web\Admin\NotificationsController;
use App\Http\Controllers\Web\Admin\Products\DeletedProductsController;
use App\Http\Controllers\Web\Admin\Products\ProductsController;
use App\Http\Controllers\Web\Admin\SellerController;
use App\Http\Controllers\Web\Admin\ServersController;
use App\Http\Controllers\Web\Admin\SettingsController;
use App\Http\Controllers\Web\Admin\Shop\HomePageController;
use App\Http\Controllers\Web\Admin\SlidersController;
use App\Http\Controllers\Web\Admin\Shop\SliderController as ShopSlidersController;
use App\Http\Controllers\Web\Admin\SubscriptionPlansController;
use App\Http\Controllers\Web\Admin\TvSeries\AttributesController;
use App\Http\Controllers\Web\Admin\TvSeries\ContentController;
use App\Http\Controllers\Web\Admin\TvSeries\MediaController;
use App\Http\Controllers\Web\Admin\TvSeries\SnapsController;
use App\Http\Controllers\Web\Admin\TvSeries\TvSeriesBase;
use App\Http\Controllers\Web\Admin\Videos\VideosBase;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Slider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

const SubDomainPrefix = 'admin';

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
			Route::get('', [CategoryController::class, Methods::Index])->name('admin.categories.index');
			Route::get('create', [CategoryController::class, Methods::Create])->name('admin.categories.create');
			Route::get('{id}/edit', [CategoryController::class, Methods::Edit])->name('admin.categories.edit');
			Route::post('store', [CategoryController::class, Methods::Store])->name('admin.categories.store');
			Route::post('{id}', [CategoryController::class, Methods::Update])->name('admin.categories.update');
			Route::delete('{id}', [CategoryController::class, Methods::Delete])->name('admin.categories.delete');
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

		// Shop Home-page Route(s)
		Route::prefix('shop')->middleware('auth:admin')->group(function (){
			Route::get('choices', [HomePageController::class, 'choices'])->name('admin.shop.choices');
			Route::get('sale-offer-timer', [HomePageController::class, 'editSaleOfferTimerDetails'])->name('admin.shop.sale-offer-timer.edit');
			Route::post('sale-offer-timer', [HomePageController::class, 'updateSaleOfferTimerDetails'])->name('admin.shop.sale-offer-timer.update');

			// Shop Sliders' Route(s)
			Route::prefix('sliders')->group(function (){
				Route::get('', [ShopSlidersController::class, 'index'])->name('admin.shop.sliders.index');
				Route::get('create', [ShopSlidersController::class, 'create'])->name('admin.shop.sliders.create');
				Route::get('{id}/edit', [ShopSlidersController::class, 'edit'])->name('admin.shop.sliders.edit');
				Route::get('{id}', [ShopSlidersController::class, 'show'])->name('admin.shop.sliders.show');
				Route::post('', [ShopSlidersController::class, 'store'])->name('admin.shop.sliders.store');
				Route::post('{id}', [ShopSlidersController::class, 'update'])->name('admin.shop.sliders.update');
				Route::put('{id}/status', [ShopSlidersController::class, 'updateStatus'])->name('admin.shop.sliders.update.status');
				Route::delete('{id}', [ShopSlidersController::class, 'delete'])->name('admin.shop.sliders.delete');
			});

			// Brands in Focus Route(s)
			Route::prefix('brands-in-focus')->group(function (){
				Route::get('', [HomePageController::class, 'editBrandsInFocus'])->name('admin.shop.brands-in-focus.edit');
				Route::post('', [HomePageController::class, 'updateBrandsInFocus'])->name('admin.shop.brands-in-focus.update');
			});

			// Hot Deals Route(s)
			Route::prefix('hot-deals')->group(function (){
				Route::get('', [HomePageController::class, 'editHotDeals'])->name('admin.shop.hot-deals.edit');
				Route::get('/{id}', [HomePageController::class, 'viewProductDetails'])->name('admin.shop.hot-deals.show');
				Route::post('', [HomePageController::class, 'updateHotDeals'])->name('admin.shop.hot-deals.update');
			});

			// Popular Stuff Route(s)
			Route::prefix('popular-category')->group(function (){
				Route::get('', [HomePageController::class, 'editPopularStuff'])->name('admin.shop.popular-category.edit');
				Route::post('', [HomePageController::class, 'updatePopularStuff'])->name('admin.shop.popular-category.update');
			});

			// Trending Now Route(s)
			Route::prefix('trending-now')->group(function (){
				Route::get('', [HomePageController::class, 'editTrendingNow'])->name('admin.shop.trending-now.edit');
				Route::post('', [HomePageController::class, 'updateTrendingNow'])->name('admin.shop.trending-now.update');
			});
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
			Route::get(Str::Empty, [ProductsController::class, Methods::Index])->name('admin.products.index');
		});

		// Attributes Route(s)
		Route::prefix('attributes')->group(function (){
			Route::get(Str::Empty, [\App\Http\Controllers\Web\Admin\Products\Attributes\AttributeController::class, 'index'])->name('admin.products.attributes.index');
			Route::get('create', [\App\Http\Controllers\Web\Admin\Products\Attributes\AttributeController::class, 'create'])->name('admin.products.attributes.create');
			Route::post(Str::Empty, [\App\Http\Controllers\Web\Admin\Products\Attributes\AttributeController::class, 'store'])->name('admin.products.attributes.store');

			Route::prefix('sets')->group(function (){
				Route::get(Str::Empty, [\App\Http\Controllers\Web\Admin\Products\Attributes\AttributeSetController::class, 'index'])->name('admin.attributes.sets.index');
				Route::get('create', [\App\Http\Controllers\Web\Admin\Products\Attributes\AttributeSetController::class, 'create'])->name('admin.attributes.sets.create');
				Route::post(Str::Empty, [\App\Http\Controllers\Web\Admin\Products\Attributes\AttributeSetController::class, 'store'])->name('admin.attributes.sets.store');
			});

			Route::prefix('values')->group(function (){
				Route::get('{attributeId}/edit', [\App\Http\Controllers\Web\Admin\Products\Attributes\AttributeValuesController::class, 'edit'])->name('admin.products.attributes.values.edit');
				Route::post('{attributeId}', [\App\Http\Controllers\Web\Admin\Products\Attributes\AttributeValuesController::class, 'store'])->name('admin.products.attributes.values.store');
			});
		});

		// Brand Route(s)
		Route::prefix('brands')->middleware('auth:admin')->group(function (){
			Route::get(Str::Empty, [\App\Http\Controllers\Web\Admin\BrandController::class, 'index'])->name('admin.brands.index');
			Route::get('create', [\App\Http\Controllers\Web\Admin\BrandController::class, 'create'])->name('admin.brands.create');
			Route::get('{id}/edit', [\App\Http\Controllers\Web\Admin\BrandController::class, 'edit'])->name('admin.brands.edit');
			Route::post(Str::Empty, [\App\Http\Controllers\Web\Admin\BrandController::class, 'store'])->name('admin.brands.store');
			Route::post('{id}', [\App\Http\Controllers\Web\Admin\BrandController::class, 'update'])->name('admin.brands.update');
		});

		// Settings Route(s)
		Route::prefix('settings')->middleware('auth:admin')->group(function (){
			Route::get('', [SettingsController::class, Methods::Index])->name('admin.settings.index');
		});
	});
});