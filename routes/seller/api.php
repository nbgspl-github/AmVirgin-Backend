<?php

use App\Http\Controllers\Api\Seller\Attributes\ListController;
use App\Http\Controllers\Api\Seller\Attributes\ProductAttributeController;
use App\Http\Controllers\Api\Seller\Attributes\ValueController;
use App\Http\Controllers\Api\Seller\AuthController;
use App\Http\Controllers\Api\Seller\CategoryController;
use App\Http\Controllers\Api\Seller\CityController;
use App\Http\Controllers\Api\Seller\CountryController;
use App\Http\Controllers\Api\Seller\CurrencyController;
use App\Http\Controllers\Api\Seller\HsnCodeController;
use App\Http\Controllers\Api\Seller\OrderController;
use App\Http\Controllers\Api\Seller\ProductImageController;
use App\Http\Controllers\Api\Seller\Products\ProductController;
use App\Http\Controllers\Api\Seller\StateController;
use App\Http\Controllers\Api\Seller\TwoFactorAuthController;
use App\Library\Utils\Extensions\Str;
use Illuminate\Support\Facades\Route;

Route::prefix(Str::Empty)->group(static function () {
	Route::get(Str::Empty, [TwoFactorAuthController::class, 'exists']);
	Route::post('login', [TwoFactorAuthController::class, 'login']);
	Route::post('register', [TwoFactorAuthController::class, 'register']);
	Route::post('logout', [AuthController::class, 'logout'])->middleware(AUTH_SELLER);

	Route::prefix('profile')->group(static function () {
		Route::get(Str::Empty, [AuthController::class, 'profile'])->middleware(AUTH_SELLER);
		Route::put(Str::Empty, [AuthController::class, 'updateProfile'])->middleware(AUTH_SELLER);
		Route::post('avatar', [AuthController::class, 'updateAvatar'])->middleware(AUTH_SELLER);
		Route::put('password', [AuthController::class, 'updatePassword'])->middleware(AUTH_SELLER);

		Route::prefix('business-details')->middleware(AUTH_SELLER)->group(static function () {
			Route::get(Str::Empty, [\App\Http\Controllers\Api\Seller\BusinessDetailController::class, 'show']);
			Route::post(Str::Empty, [\App\Http\Controllers\Api\Seller\BusinessDetailController::class, 'update']);
		});

		Route::prefix('bank-details')->middleware(AUTH_SELLER)->group(static function () {
			Route::get(Str::Empty, [\App\Http\Controllers\Api\Seller\BankDetailController::class, 'show']);
			Route::post(Str::Empty, [\App\Http\Controllers\Api\Seller\BankDetailController::class, 'update']);
		});

		Route::prefix('contact-details')->group(static function () {

		});

		Route::prefix('pickup-details')->group(static function () {

		});

		Route::prefix('mou')->group(static function () {
			Route::get(Str::Empty, [\App\Http\Controllers\Api\Seller\AgreementController::class, 'show']);
			Route::get('status', [\App\Http\Controllers\Api\Seller\AgreementController::class, 'index'])->middleware(AUTH_SELLER);
			Route::put(Str::Empty, [\App\Http\Controllers\Api\Seller\AgreementController::class, 'update'])->middleware(AUTH_SELLER);
		});
	});
});
Route::post('change-password', [AuthController::class, 'changePassword'])->middleware(AUTH_SELLER);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('change-email', [AuthController::class, 'changeEmail'])->middleware(AUTH_SELLER);
Route::post('change-email-token', [AuthController::class, 'getChangeEmailToken'])->middleware(AUTH_SELLER);
Route::post('reset-password-token', [AuthController::class, 'getResetPasswordToken']);

Route::prefix('categories')->group(function () {
	Route::get('/', [CategoryController::class, 'index'])->name('seller.categories.index');
	Route::get('/{id}/attributes', [ListController::class, 'show'])->name('seller.categories.attributes.index');
});

Route::prefix('attributes')->group(function () {
	Route::get('/{attributeId}/values', [ValueController::class, 'show']);
});

Route::middleware(AUTH_SELLER)->prefix('products')->group(function () {
	Route::get('/', [ProductController::class, 'index'])->name('seller.products.index');
	Route::post(Str::Empty, [ProductController::class, 'store'])->name('seller.products.store');
	Route::get('{id}', [ProductController::class, 'show'])->name('seller.products.show');
	Route::get('edit/{id}', [ProductController::class, 'edit'])->name('seller.products.edit');
	Route::post('{id}', [ProductController::class, 'update'])->name('seller.products.update');
	Route::post('change-status/{id}', [ProductController::class, 'changeStatus'])->name('seller.products.change-status');
	Route::delete('{id}', [ProductController::class, 'delete'])->name('seller.products.delete');
	Route::delete('/images/{id}', [ProductImageController::class, 'delete'])->name('seller.products.images.delete');
	Route::delete('/attributes/{id}', [ProductAttributeController::class, 'delete'])->name('seller.products.attributes.delete');

	Route::prefix('token')->group(function () {
		Route::get('create', [ProductController::class, 'token']);
	});

	Route::prefix('trailer')->group(function () {
		Route::post('upload', [\App\Http\Controllers\Api\Seller\Products\ProductTrailerController::class, 'store']);
	});
});

Route::prefix('currencies')->group(function () {
	Route::get('/', [CurrencyController::class, 'index'])->name('seller.currencies.index');
});

Route::prefix('countries')->group(function () {
	Route::get('/', [CountryController::class, 'index'])->name('seller.countries.index');
	Route::get('{countryId}/states', [StateController::class, 'index'])->name('seller.states.index');
	Route::get('states/{stateId}/cities', [CityController::class, 'index'])->name('seller.states.index');
});

Route::prefix('orders')->middleware('auth:seller-api')->group(function () {
	Route::get(Str::Empty, [\App\Http\Controllers\Api\Seller\Orders\OrderController::class, 'index']);
	Route::get('{order}', [\App\Http\Controllers\Api\Seller\Orders\OrderController::class, 'show']);
	Route::put('{order}/status', [\App\Http\Controllers\Api\Seller\Orders\Status\ActionController::class, 'handle']);
	Route::put('status', [\App\Http\Controllers\Api\Seller\Orders\Status\BulkStatusController::class, 'update']);
});

Route::prefix('hsn')->group(function () {
	Route::get('/', [HsnCodeController::class, 'index']);
});

Route::prefix('brands')->middleware('auth:seller-api')->group(function () {
	Route::get(Str::Empty, [\App\Http\Controllers\Api\Seller\BrandController::class, 'index']);
	Route::get('approved', [\App\Http\Controllers\Api\Seller\BrandController::class, 'show']);
	Route::get('all', [\App\Http\Controllers\Api\Seller\ApprovedBrandController::class, 'index']);
	Route::post('approval', [\App\Http\Controllers\Api\Seller\BrandController::class, 'store']);
});

Route::prefix('announcements')->group(function () {
	Route::get(Str::Empty, [\App\Http\Controllers\Api\Seller\AnnouncementController::class, 'index'])->middleware(AUTH_SELLER);
	Route::put('{id}/mark', [\App\Http\Controllers\Api\Seller\AnnouncementController::class, 'mark'])->middleware(AUTH_SELLER);
});

Route::prefix('support')->group(static function () {
	Route::prefix('tickets')->group(static function () {
		Route::get(Str::Empty, [\App\Http\Controllers\Api\Seller\SupportController::class, 'index'])->middleware(AUTH_SELLER);
		Route::post(Str::Empty, [\App\Http\Controllers\Api\Seller\SupportController::class, 'store'])->middleware(AUTH_SELLER);
	});
});

Route::prefix('payments')->group(static function () {
	Route::get('overview', [\App\Http\Controllers\Api\Seller\Payments\PaymentController::class, 'index']);
	Route::get('previous', [\App\Http\Controllers\Api\Seller\Payments\HistoryController::class, 'index']);
	Route::get('transactions', [\App\Http\Controllers\Api\Seller\Payments\TransactionController::class, 'index']);
});

Route::prefix('sales')->group(static function () {
	Route::get(Str::Empty, [\App\Http\Controllers\Api\Seller\Payments\OverviewController::class, 'totalSales'])->middleware(AUTH_SELLER);
});

Route::prefix('growth')->group(static function () {
	Route::get('overview', [\App\Http\Controllers\Api\Seller\Growth\OverviewController::class, 'show'])->middleware(AUTH_SELLER);
});

Route::prefix('promotions')->middleware(AUTH_SELLER)->group(static function () {
	Route::get(Str::Empty, [\App\Http\Controllers\Api\Seller\AdvertisementController::class, 'index']);
	Route::post(Str::Empty, [\App\Http\Controllers\Api\Seller\AdvertisementController::class, 'store']);
	Route::get('{advertisement}', [\App\Http\Controllers\Api\Seller\AdvertisementController::class, 'show']);
	Route::post('{advertisement}/update', [\App\Http\Controllers\Api\Seller\AdvertisementController::class, 'update']);
	Route::delete('{advertisement}', [\App\Http\Controllers\Api\Seller\AdvertisementController::class, 'delete']);
});

Route::prefix('bulk')->group(static function () {
	Route::get(Str::Empty, [\App\Http\Controllers\Api\Seller\Products\BulkTemplateController::class, 'show']);
	Route::post(Str::Empty, [\App\Http\Controllers\Api\Seller\Products\BulkProductController::class, 'store'])->middleware(AUTH_SELLER);
	Route::post('images', [\App\Http\Controllers\Api\Seller\Products\BulkImageController::class, 'store']);
});

Route::prefix('manifest')->group(static function () {
	Route::get('download', [\App\Http\Controllers\Api\Seller\Manifest\ManifestController::class, 'update']);
});

Route::prefix('dashboard')->group(static function () {
	Route::get(Str::Empty, [\App\Http\Controllers\Api\Seller\DashboardController::class, 'index'])->middleware(AUTH_SELLER);
});

Route::prefix('returns')->group(function () {
	Route::get(Str::Empty, [\App\Http\Controllers\Api\Seller\Orders\Returns\ReturnController::class, 'index']);
	Route::prefix('{return}')->group(function () {
		Route::post('approve', [\App\Http\Controllers\Api\Seller\Orders\Returns\ReturnController::class, 'approve']);
		Route::post('disapprove', [\App\Http\Controllers\Api\Seller\Orders\Returns\ReturnController::class, 'disapprove']);
	});
});