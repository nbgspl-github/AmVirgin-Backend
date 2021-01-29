<?php

use App\Http\Modules\Admin\Controllers\Web\Auth\LoginController;
use App\Http\Modules\Admin\Controllers\Web\CategoryController;
use App\Http\Modules\Admin\Controllers\Web\GenreController;
use App\Http\Modules\Admin\Controllers\Web\HomeController;
use App\Http\Modules\Admin\Controllers\Web\News\ArticleController;
use App\Http\Modules\Admin\Controllers\Web\News\Articles\ContentController;
use App\Http\Modules\Admin\Controllers\Web\News\Articles\VideoController as ArticlesVideoController;
use App\Http\Modules\Admin\Controllers\Web\News\CategoryController as NewsCategoryController;
use App\Http\Modules\Admin\Controllers\Web\Products\Attributes\DetailController;
use App\Http\Modules\Admin\Controllers\Web\Products\ProductController;
use App\Http\Modules\Admin\Controllers\Web\Shop\HomePageController;
use App\Http\Modules\Admin\Controllers\Web\Shop\SliderController as ShopSliderController;
use App\Http\Modules\Admin\Controllers\Web\SliderController;
use App\Http\Modules\Admin\Controllers\Web\SubscriptionPlanController;
use App\Http\Modules\Admin\Controllers\Web\TvSeries\AttributeController;
use App\Http\Modules\Admin\Controllers\Web\TvSeries\TvSeriesController;
use App\Http\Modules\Admin\Controllers\Web\Users\CustomerController;
use App\Http\Modules\Admin\Controllers\Web\Users\SellerController;
use App\Http\Modules\Admin\Controllers\Web\Videos\VideoController;
use App\Library\Utils\Extensions\Str;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->middleware('auth:admin')->name('admin.home');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login', [LoginController::class, 'login'])->name('admin.login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');
Route::middleware('auth:admin')->group(function () {

	// Customer's Route(s)
	Route::prefix('customers')->group(function () {
		Route::get(Str::Empty, [CustomerController::class, 'index'])->name('admin.customers.index');
		Route::get('create', [CustomerController::class, 'create'])->name('admin.customers.create');
		Route::get('{customer}/edit', [CustomerController::class, 'edit'])->name('admin.customers.edit');
		Route::get('{customer}', [CustomerController::class, 'show'])->name('admin.customers.show');
		Route::post(Str::Empty, [CustomerController::class, 'store'])->name('admin.customers.store');
		Route::post('{customer}', [CustomerController::class, 'update'])->name('admin.customers.update');
		Route::put('{customer}/status', [CustomerController::class, 'updateStatus'])->name('admin.customers.update.status');
		Route::delete('{customer}', [CustomerController::class, 'delete'])->name('admin.customers.delete');
	});

	// Seller's Route(s)
	Route::prefix('sellers')->group(function () {
		Route::get(Str::Empty, [SellerController::class, 'index'])->name('admin.sellers.index');
		Route::get('create', [SellerController::class, 'create'])->name('admin.sellers.create');
		Route::get('{seller}/edit', [SellerController::class, 'edit'])->name('admin.sellers.edit');
		Route::get('{seller}', [SellerController::class, 'show'])->name('admin.sellers.show');
		Route::post('', [SellerController::class, 'store'])->name('admin.sellers.store');
		Route::post('{seller}', [SellerController::class, 'update'])->name('admin.sellers.update');
		Route::put('{seller}/status', [SellerController::class, 'updateStatus'])->name('admin.sellers.update.status');
		Route::delete('{seller}', [SellerController::class, 'delete'])->name('admin.sellers.delete');
	});

	// News Categories Route(s)
	Route::prefix('news')->group(function () {
		Route::prefix('articles')->group(function () {
			Route::get(Str::Empty, [ArticleController::class, 'index'])->name('admin.news.articles.index');
			Route::get('{article/edit}', [ArticleController::class, 'edit'])->name('admin.news.articles.edit');
			Route::prefix('content')->group(function () {
				Route::get('create', [ContentController::class, 'create'])->name('admin.news.articles.content.create');
				Route::get('{article}/edit', [ContentController::class, 'edit'])->name('admin.news.articles.content.edit');
				Route::post(Str::Empty, [ContentController::class, 'store'])->name('admin.news.articles.content.store');
				Route::post('{article}', [ContentController::class, 'update'])->name('admin.news.articles.content.update');
			});
			Route::prefix('videos')->group(function () {
				Route::get('create', [ArticlesVideoController::class, 'create'])->name('admin.news.articles.videos.create');
				Route::get('{article}/edit', [ArticlesVideoController::class, 'edit'])->name('admin.news.articles.videos.edit');
				Route::post(Str::Empty, [ArticlesVideoController::class, 'store'])->name('admin.news.articles.videos.store');
				Route::post('{article}', [ArticlesVideoController::class, 'update'])->name('admin.news.articles.videos.update');
			});
			Route::get('{article}/edit', [ArticleController::class, 'edit'])->name('admin.news.articles.edit');
			Route::post(Str::Empty, [ArticleController::class, 'store'])->name('admin.news.articles.store');
			Route::post('{article}', [ArticleController::class, 'update'])->name('admin.news.articles.update');
			Route::delete('{article}', [ArticleController::class, 'delete'])->name('admin.news.articles.delete');
		});
		Route::prefix('categories')->group(function () {
			Route::get(Str::Empty, [NewsCategoryController::class, 'index'])->name('admin.news.categories.index');
			Route::get('create', [NewsCategoryController::class, 'create'])->name('admin.news.categories.create');
			Route::get('{category}/edit', [NewsCategoryController::class, 'edit'])->name('admin.news.categories.edit');
			Route::post(Str::Empty, [NewsCategoryController::class, 'store'])->name('admin.news.categories.store');
			Route::post('{category}', [NewsCategoryController::class, 'update'])->name('admin.news.categories.update');
			Route::delete('{category}', [NewsCategoryController::class, 'delete'])->name('admin.news.categories.delete');
		});
	});

	// Categories Route(s)
	Route::prefix('categories')->middleware('auth:admin')->group(function () {
		Route::get(Str::Empty, [CategoryController::class, 'index'])->name('admin.categories.index');
		Route::get('create', [CategoryController::class, 'create'])->name('admin.categories.create');
		Route::get('{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
		Route::post('store', [CategoryController::class, 'store'])->name('admin.categories.store');
		Route::post('{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
		Route::delete('{category}', [CategoryController::class, 'delete'])->name('admin.categories.delete');
		Route::get('{id}/download', [CategoryController::class, 'downloadTemplate'])->name('admin.categories.download');
	});

	// Orders Route(s)
	Route::prefix('orders')->group(function () {
		Route::get(Str::Empty, [\App\Http\Modules\Admin\Controllers\Web\Orders\OrderController::class, 'index'])->name('admin.orders.index');
		Route::get('{order}', [\App\Http\Modules\Admin\Controllers\Web\Orders\OrderController::class, 'show'])->name('admin.orders.show');
	});

	// Videos Route(s)
	Route::prefix('videos')->middleware('auth:admin')->group(function () {
		Route::get('', [VideoController::class, 'index'])->name('admin.videos.index');
		Route::get('actions/{video}', [VideoController::class, 'choose'])->name('admin.videos.edit.action');
		Route::get('create', [VideoController::class, 'create'])->name('admin.videos.create');
		Route::prefix('edit/{video}')->group(function () {
			Route::get('attributes', [\App\Http\Modules\Admin\Controllers\Web\Videos\AttributeController::class, 'edit'])->name('admin.videos.edit.attributes');
			Route::get('content', [\App\Http\Modules\Admin\Controllers\Web\Videos\ContentController::class, 'edit'])->name('admin.videos.edit.content');
			Route::get('media', [\App\Http\Modules\Admin\Controllers\Web\Videos\MediaController::class, 'edit'])->name('admin.videos.edit.media');
			Route::get('snaps', [\App\Http\Modules\Admin\Controllers\Web\Videos\SnapController::class, 'edit'])->name('admin.videos.edit.snaps');
			Route::get('audio', [\App\Http\Modules\Admin\Controllers\Web\Videos\AudioController::class, 'index'])->name('admin.videos.edit.audio');
			Route::get('subtitle', [\App\Http\Modules\Admin\Controllers\Web\Videos\SubtitleController::class, 'index'])->name('admin.videos.edit.subtitle');
			Route::get('source', [\App\Http\Modules\Admin\Controllers\Web\Videos\SourceController::class, 'edit'])->name('admin.videos.edit.source');
		});
		Route::prefix('{video}/update')->group(function () {
			Route::post('attributes', [\App\Http\Modules\Admin\Controllers\Web\Videos\AttributeController::class, 'update'])->name('admin.videos.update.attributes');
			Route::post('content', [\App\Http\Modules\Admin\Controllers\Web\Videos\ContentController::class, 'update'])->name('admin.videos.update.content');
			Route::post('media', [\App\Http\Modules\Admin\Controllers\Web\Videos\MediaController::class, 'update'])->name('admin.videos.update.media');
			Route::post('snaps', [\App\Http\Modules\Admin\Controllers\Web\Videos\SnapController::class, 'update'])->name('admin.videos.update.snaps');
			Route::post('audio', [\App\Http\Modules\Admin\Controllers\Web\Videos\AudioController::class, 'store'])->name('admin.videos.update.audio');
			Route::post('subtitle', [\App\Http\Modules\Admin\Controllers\Web\Videos\SubtitleController::class, 'store'])->name('admin.videos.update.subtitle');
			Route::post('source', [\App\Http\Modules\Admin\Controllers\Web\Videos\SourceController::class, 'update'])->name('admin.videos.update.source');
			Route::match(['get', 'post'], 'source/chunk', [\App\Http\Modules\Admin\Controllers\Web\Videos\SourceController::class, 'chunk'])->name('admin.videos.update.source.chunk');
		});
		Route::post('store', [VideoController::class, 'store'])->name('admin.videos.store');
		Route::prefix('{video}')->group(function () {
			Route::delete('', [VideoController::class, 'destroy'])->name('admin.videos.delete');
			Route::delete('content/{contentId}', [\App\Http\Modules\Admin\Controllers\Web\Videos\ContentController::class, 'delete'])->name('admin.videos.delete.content');
			Route::delete('snaps/{snap}', [\App\Http\Modules\Admin\Controllers\Web\Videos\SnapController::class, 'delete'])->name('admin.videos.delete.snaps');
			Route::delete('audios/{audio}', [\App\Http\Modules\Admin\Controllers\Web\Videos\AudioController::class, 'delete'])->name('admin.videos.delete.audio');
			Route::delete('subtitles/{subtitle}', [\App\Http\Modules\Admin\Controllers\Web\Videos\SubtitleController::class, 'delete'])->name('admin.videos.delete.subtitle');
		});
	});

	// TV Series Route(s)
	Route::prefix('tv-series')->middleware('auth:admin')->group(function () {
		Route::get('', [TvSeriesController::class, 'index'])->name('admin.tv-series.index');
		Route::get('actions/{video}', [TvSeriesController::class, 'choose'])->name('admin.tv-series.edit.action');
		Route::get('create', [TvSeriesController::class, 'create'])->name('admin.tv-series.create');
		Route::prefix('edit/{video}')->group(function () {
			Route::get('attributes', [AttributeController::class, 'edit'])->name('admin.tv-series.edit.attributes');
			Route::get('source', [\App\Http\Modules\Admin\Controllers\Web\TvSeries\SourceController::class, 'index'])->name('admin.tv-series.edit.source');
			Route::get('sources/{source}/audio', [\App\Http\Modules\Admin\Controllers\Web\TvSeries\AudioController::class, 'index'])->name('admin.tv-series.edit.audio');
			Route::get('sources/{source}/subtitle', [\App\Http\Modules\Admin\Controllers\Web\TvSeries\SubtitleController::class, 'index'])->name('admin.tv-series.edit.subtitle');
		});
		Route::prefix('{video}/update')->group(function () {
			Route::post('attributes', [AttributeController::class, 'update'])->name('admin.tv-series.update.attributes');
			Route::post('{source}/audio', [\App\Http\Modules\Admin\Controllers\Web\TvSeries\AudioController::class, 'store'])->name('admin.tv-series.update.audio');
			Route::post('{source}/subtitle', [\App\Http\Modules\Admin\Controllers\Web\TvSeries\SubtitleController::class, 'store'])->name('admin.tv-series.update.subtitle');
			Route::post('source', [\App\Http\Modules\Admin\Controllers\Web\TvSeries\SourceController::class, 'store'])->name('admin.tv-series.update.source');
		});
		Route::post('store', [TvSeriesController::class, 'store'])->name('admin.tv-series.store');
		Route::prefix('{video}')->group(function () {
			Route::delete('', [TvSeriesController::class, 'delete'])->name('admin.tv-series.delete');
		});
	});

	// Genres Route(s)
	Route::prefix('genres')->middleware('auth:admin')->group(function () {
		Route::get(Str::Empty, [GenreController::class, 'index'])->name('admin.genres.index');
		Route::get('create', [GenreController::class, 'create'])->name('admin.genres.create');
		Route::get('{genre}/edit', [GenreController::class, 'edit'])->name('admin.genres.edit');
		Route::get('{genre}', [GenreController::class, 'show'])->name('admin.genres.show');
		Route::post(Str::Empty, [GenreController::class, 'store'])->name('admin.genres.store');
		Route::post('{genre}', [GenreController::class, 'update'])->name('admin.genres.update');
		Route::put('{genre}/status', [GenreController::class, 'status'])->name('admin.genres.update.status');
		Route::delete('{genre}', [GenreController::class, 'delete'])->name('admin.genres.delete');
	});

	// Sliders Route(s)
	Route::prefix('sliders')->group(function () {
		Route::get(Str::Empty, [SliderController::class, 'index'])->name('admin.sliders.index');
		Route::get('create', [SliderController::class, 'create'])->name('admin.sliders.create');
		Route::get('{slider}/edit', [SliderController::class, 'edit'])->name('admin.sliders.edit');
		Route::post(Str::Empty, [SliderController::class, 'store'])->name('admin.sliders.store');
		Route::post('{slider}', [SliderController::class, 'update'])->name('admin.sliders.update');
		Route::put('{slider}/status', [SliderController::class, 'status'])->name('admin.sliders.update.status');
		Route::delete('{slider}', [SliderController::class, 'delete'])->name('admin.sliders.delete');
	});

	// Shop Home-page Route(s)
	Route::prefix('shop')->middleware('auth:admin')->group(function () {
		Route::get('choices', [HomePageController::class, 'choices'])->name('admin.shop.choices');
		Route::get('sale-offer-timer', [HomePageController::class, 'editSaleOfferTimerDetails'])->name('admin.shop.sale-offer-timer.edit');
		Route::post('sale-offer-timer', [HomePageController::class, 'updateSaleOfferTimerDetails'])->name('admin.shop.sale-offer-timer.update');

		// Shop Sliders' Route(s)
		Route::prefix('sliders')->group(function () {
			Route::get(Str::Empty, [ShopSliderController::class, 'index'])->name('admin.shop.sliders.index');
			Route::get('create', [ShopSliderController::class, 'create'])->name('admin.shop.sliders.create');
			Route::get('{slider}/edit', [ShopSliderController::class, 'edit'])->name('admin.shop.sliders.edit');
			Route::get('{id}', [ShopSliderController::class, 'show'])->name('admin.shop.sliders.show');
			Route::post(Str::Empty, [ShopSliderController::class, 'store'])->name('admin.shop.sliders.store');
			Route::post('{slider}', [ShopSliderController::class, 'update'])->name('admin.shop.sliders.update');
			Route::put('{id}/status', [ShopSliderController::class, 'updateStatus'])->name('admin.shop.sliders.update.status');
			Route::delete('{id}', [ShopSliderController::class, 'delete'])->name('admin.shop.sliders.delete');
		});

		// Brands in Focus Route(s)
		Route::prefix('brands-in-focus')->group(function () {
			Route::get('', [HomePageController::class, 'editBrandsInFocus'])->name('admin.shop.brands-in-focus.edit');
			Route::post('', [HomePageController::class, 'updateBrandsInFocus'])->name('admin.shop.brands-in-focus.update');
		});

		// Hot Deals Route(s)
		Route::prefix('hot-deals')->group(function () {
			Route::get('', [HomePageController::class, 'editHotDeals'])->name('admin.shop.hot-deals.edit');
			Route::get('/{id}', [HomePageController::class, 'viewProductDetails'])->name('admin.shop.hot-deals.show');
			Route::post('', [HomePageController::class, 'updateHotDeals'])->name('admin.shop.hot-deals.update');
		});

		// Popular Stuff Route(s)
		Route::prefix('popular-category')->group(function () {
			Route::get('', [HomePageController::class, 'editPopularStuff'])->name('admin.shop.popular-category.edit');
			Route::post('', [HomePageController::class, 'updatePopularStuff'])->name('admin.shop.popular-category.update');
		});

		// Trending Now Route(s)
		Route::prefix('trending-now')->group(function () {
			Route::get('', [HomePageController::class, 'editTrendingNow'])->name('admin.shop.trending-now.edit');
			Route::post('', [HomePageController::class, 'updateTrendingNow'])->name('admin.shop.trending-now.update');
		});
	});

	// Subscription Plan Route(s)
	Route::prefix('subscription-plans')->group(function () {
		Route::get(Str::Empty, [SubscriptionPlanController::class, 'index'])->name('admin.subscription-plans.index');
		Route::get('create', [SubscriptionPlanController::class, 'create'])->name('admin.subscription-plans.create');
		Route::get('{plan}/edit', [SubscriptionPlanController::class, 'edit'])->name('admin.subscription-plans.edit');
		Route::get('{plan}', [SubscriptionPlanController::class, 'show'])->name('admin.subscription-plans.show');
		Route::post(Str::Empty, [SubscriptionPlanController::class, 'store'])->name('admin.subscription-plans.store');
		Route::post('{plan}', [SubscriptionPlanController::class, 'update'])->name('admin.subscription-plans.update');
		Route::put('{plan}/status', [SubscriptionPlanController::class, 'status'])->name('admin.subscription-plans.update.status');
		Route::delete('{plan}', [SubscriptionPlanController::class, 'delete'])->name('admin.subscription-plans.delete');
	});

	// Products Route(s)
	Route::prefix('products')->group(function () {
		Route::prefix('pending')->group(function () {
			Route::get(Str::Empty, [ProductController::class, 'pending'])->name('admin.products.pending');
			Route::get('{product}', [DetailController::class, 'pending'])->name('admin.products.pending.details');
			Route::get('{product}/approve', [DetailController::class, 'approve'])->name('admin.products.pending.approve');
			Route::get('{product}/reject', [DetailController::class, 'reject'])->name('admin.products.pending.reject');
		});
		Route::prefix('approved')->group(function () {
			Route::get(Str::Empty, [ProductController::class, 'approved'])->name('admin.products.approved');
			Route::get('{product}', [DetailController::class, 'approved'])->name('admin.products.approved.details');
		});
		Route::prefix('rejected')->group(function () {
			Route::get(Str::Empty, [ProductController::class, 'rejected'])->name('admin.products.rejected');
			Route::get('{product}', [DetailController::class, 'rejected'])->name('admin.products.rejected.details');
		});
		Route::prefix('deleted')->group(function () {
			Route::get(Str::Empty, [ProductController::class, 'deleted'])->name('admin.products.deleted');
			Route::get('{product}', [DetailController::class, 'deleted'])->name('admin.products.deleted.details');
		});
	});

	// Attributes Route(s)
	Route::prefix('attributes')->group(function () {
		Route::get(Str::Empty, [\App\Http\Modules\Admin\Controllers\Web\Products\Attributes\AttributeController::class, 'index'])->name('admin.products.attributes.index');
		Route::get('{attribute}/edit', [\App\Http\Modules\Admin\Controllers\Web\Products\Attributes\AttributeController::class, 'edit'])->name('admin.products.attributes.edit');
		Route::get('create', [\App\Http\Modules\Admin\Controllers\Web\Products\Attributes\AttributeController::class, 'create'])->name('admin.products.attributes.create');
		Route::post(Str::Empty, [\App\Http\Modules\Admin\Controllers\Web\Products\Attributes\AttributeController::class, 'store'])->name('admin.products.attributes.store');
		Route::post('{attribute}', [\App\Http\Modules\Admin\Controllers\Web\Products\Attributes\AttributeController::class, 'update'])->name('admin.products.attributes.update');

		Route::prefix('sets')->group(function () {
			Route::get(Str::Empty, [\App\Http\Modules\Admin\Controllers\Web\Products\Attributes\AttributeSetController::class, 'index'])->name('admin.attributes.sets.index');
			Route::get('create', [\App\Http\Modules\Admin\Controllers\Web\Products\Attributes\AttributeSetController::class, 'create'])->name('admin.attributes.sets.create');
			Route::post('store', [\App\Http\Modules\Admin\Controllers\Web\Products\Attributes\AttributeSetController::class, 'store'])->name('admin.attributes.sets.store');
			Route::delete('{category}', [\App\Http\Modules\Admin\Controllers\Web\Products\Attributes\AttributeSetController::class, 'delete'])->name('admin.attributes.sets.store');
		});

		Route::prefix('values')->group(function () {
			Route::get('{attributeId}/edit', [\App\Http\Modules\Admin\Controllers\Web\Products\Attributes\AttributeValuesController::class, 'edit'])->name('admin.products.attributes.values.edit');
			Route::post('{attributeId}', [\App\Http\Modules\Admin\Controllers\Web\Products\Attributes\AttributeValuesController::class, 'store'])->name('admin.products.attributes.values.store');
		});
	});

	// Brand Route(s)
	Route::prefix('brands')->group(function () {
		Route::get(Str::Empty, [\App\Http\Modules\Admin\Controllers\Web\BrandController::class, 'index'])->name('admin.brands.index');
		Route::get('create', [\App\Http\Modules\Admin\Controllers\Web\BrandController::class, 'create'])->name('admin.brands.create');
		Route::get('{brand}/show', [\App\Http\Modules\Admin\Controllers\Web\BrandController::class, 'show'])->name('admin.brands.show');
		Route::get('{id}/approve', [\App\Http\Modules\Admin\Controllers\Web\BrandController::class, 'approve'])->name('admin.brands.approve');
		Route::post('{brand}', [\App\Http\Modules\Admin\Controllers\Web\BrandController::class, 'update'])->name('admin.brands.update');
		Route::delete('{brand}', [\App\Http\Modules\Admin\Controllers\Web\BrandController::class, 'delete'])->name('admin.brands.delete');
	});
});