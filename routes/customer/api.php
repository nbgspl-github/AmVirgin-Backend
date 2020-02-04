<?php

use App\Http\Controllers\App\Customer\AuthController as CustomerAuth;
use App\Http\Controllers\App\Customer\Playback\PlaybackController;
use App\Http\Controllers\App\Customer\Playback\TrailerPlayback;
use App\Http\Controllers\App\Customer\PopularPicksController;
use App\Http\Controllers\App\Customer\SlidersController as CustomerSlidersController;
use App\Http\Controllers\App\Customer\TrendController;
use App\Http\Controllers\App\Customer\TwoFactorAuthController as Customer2FAuth;
use Illuminate\Support\Facades\Route;

Route::prefix('customer')->group(function (){
	Route::get('/', [Customer2FAuth::class, 'exists'])->name('customer.check');
	Route::get('/profile', [CustomerAuth::class, 'profile'])->name('customer.profile')->middleware('auth:customer-api');
	Route::post('/login', [Customer2FAuth::class, 'login'])->name('customer.login');
	Route::post('/register', [Customer2FAuth::class, 'register'])->name('customer.register');
	Route::post('/logout', [CustomerAuth::class, 'logout'])->name('customer.logout')->middleware('auth:customer-api');
	Route::get('/profile', [CustomerAuth::class, 'profile'])->name('customer.profile')->middleware('auth:customer-api');

	Route::prefix('sliders')->group(function (){
		Route::get('/', [CustomerSlidersController::class, 'index'])->name('customer.sliders.index');
	});

	Route::prefix('trending')->group(function (){
		Route::get('/picks', [TrendController::class, 'index'])->name('customer.trending.picks');
	});

	Route::prefix('popular')->group(function (){
		Route::get('', [PopularPicksController::class, 'index'])->name('customer.popular.picks');
	});

	Route::prefix('videos')->group(function (){
		Route::get('/{slug}', [\App\Http\Controllers\App\Customer\VideosController::class, 'show']);
	});

	Route::prefix('categories')->group(function (){
		Route::get('/', [\App\Http\Controllers\App\Seller\CategoriesController::class, 'index']);
	});

	Route::prefix('sessions')->group(function (){
		Route::get('/start', [\App\Http\Controllers\App\Customer\Session\SessionController::class, 'create']);
		Route::get('/{sessionId}', [\App\Http\Controllers\App\Customer\Session\SessionController::class, 'check']);
	});

	Route::prefix('playback')->middleware([])->group(function (){
		Route::prefix('trailer')->group(function (){
			Route::get('video/{slug}', [TrailerPlayback::class, 'video']);
			Route::get('tv-series/{slug}', [TrailerPlayback::class, 'series']);
			Route::get('product/{slug}', [TrailerPlayback::class, 'product']);
		});
		Route::get('video', [PlaybackController::class, 'video']);
		Route::get('tv-series', [PlaybackController::class, 'series']);
	});

	Route::get('/test', function (){
		event(new \App\Events\Admin\TvSeries\TvSeriesUpdated(1));
	});
});