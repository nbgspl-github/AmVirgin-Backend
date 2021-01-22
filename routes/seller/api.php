<?php

use App\Http\Modules\Seller\Controllers\Api\Attributes\ListController;
use App\Http\Modules\Seller\Controllers\Api\Attributes\ProductAttributeController;
use App\Http\Modules\Seller\Controllers\Api\Attributes\ValueController;
use App\Http\Modules\Seller\Controllers\Api\Auth\AuthController;
use App\Http\Modules\Seller\Controllers\Api\Products\CategoryController;
use App\Http\Modules\Seller\Controllers\Api\Products\HsnCodeController;
use App\Http\Modules\Seller\Controllers\Api\Products\ProductController;
use App\Http\Modules\Seller\Controllers\Api\Products\ProductImageController;
use App\Http\Modules\Seller\Controllers\Api\Shared\CityController;
use App\Http\Modules\Seller\Controllers\Api\Shared\CountryController;
use App\Http\Modules\Seller\Controllers\Api\Shared\CurrencyController;
use App\Http\Modules\Seller\Controllers\Api\Shared\StateController;
use App\Library\Utils\Extensions\Str;
use Illuminate\Support\Facades\Route;

Route::get('exists', [\App\Http\Modules\Seller\Controllers\Api\Auth\ExistenceController::class, 'exists']);
Route::post('login', [\App\Http\Modules\Seller\Controllers\Api\Auth\LoginController::class, 'login']);
Route::post('logout', [\App\Http\Modules\Seller\Controllers\Api\Auth\LoginController::class, 'logout']);
Route::post('register', [\App\Http\Modules\Seller\Controllers\Api\Auth\RegisterController::class, 'register']);

Route::prefix('profile')->group(static function () {
	Route::get(Str::Empty, [\App\Http\Modules\Seller\Controllers\Api\Auth\ProfileController::class, 'show']);
	Route::put(Str::Empty, [\App\Http\Modules\Seller\Controllers\Api\Auth\ProfileController::class, 'update']);
	Route::post('avatar', [\App\Http\Modules\Seller\Controllers\Api\Auth\AvatarController::class, 'update']);
	Route::put('password', [\App\Http\Modules\Seller\Controllers\Api\Auth\PasswordController::class, 'update']);

	Route::prefix('business-details')->middleware(AUTH_SELLER)->group(static function () {
		Route::get(Str::Empty, [\App\Http\Modules\Seller\Controllers\Api\Auth\BusinessDetailController::class, 'show']);
		Route::post(Str::Empty, [\App\Http\Modules\Seller\Controllers\Api\Auth\BusinessDetailController::class, 'update']);
	});
	Route::prefix('bank-details')->middleware(AUTH_SELLER)->group(static function () {
		Route::get(Str::Empty, [\App\Http\Modules\Seller\Controllers\Api\Auth\BankDetailController::class, 'show']);
		Route::post(Str::Empty, [\App\Http\Modules\Seller\Controllers\Api\Auth\BankDetailController::class, 'update']);
	});
	Route::prefix('mou')->group(static function () {
		Route::get(Str::Empty, [\App\Http\Modules\Seller\Controllers\Api\Agreements\AgreementController::class, 'show']);
		Route::get('status', [\App\Http\Modules\Seller\Controllers\Api\Agreements\AgreementController::class, 'index'])->middleware(AUTH_SELLER);
		Route::put(Str::Empty, [\App\Http\Modules\Seller\Controllers\Api\Agreements\AgreementController::class, 'update'])->middleware(AUTH_SELLER);
	});
});
Route::post('change-password', [AuthController::class, 'changePassword'])->middleware(AUTH_SELLER);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('change-email', [AuthController::class, 'changeEmail'])->middleware(AUTH_SELLER);
Route::post('change-email-token', [AuthController::class, 'getChangeEmailToken'])->middleware(AUTH_SELLER);
Route::post('reset-password-token', [AuthController::class, 'getResetPasswordToken']);

Route::prefix('categories')->group(function () {
	Route::get(Str::Empty, [CategoryController::class, 'index'])->name('seller.categories.index');
	Route::get('{category}/attributes', [ListController::class, 'show'])->name('seller.categories.attributes.index');
});

Route::prefix('attributes')->group(function () {
	Route::get('{attributeId}/values', [ValueController::class, 'show']);
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
		Route::post('upload', [\App\Http\Modules\Seller\Controllers\Api\Products\ProductTrailerController::class, 'store']);
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

Route::prefix('orders')->group(function () {
	Route::get(Str::Empty, [\App\Http\Modules\Seller\Controllers\Api\Orders\OrderController::class, 'index']);
	Route::get('{order}', [\App\Http\Modules\Seller\Controllers\Api\Orders\OrderController::class, 'show']);
	Route::put('{order}/status', [\App\Http\Modules\Seller\Controllers\Api\Orders\Status\ActionController::class, 'handle']);
	Route::put('status', [\App\Http\Modules\Seller\Controllers\Api\Orders\Status\BulkActionController::class, 'handle']);
});

Route::prefix('hsn')->group(function () {
	Route::get('/', [HsnCodeController::class, 'index']);
});

Route::prefix('brands')->middleware('auth:seller-api')->group(function () {
	Route::get(Str::Empty, [\App\Http\Modules\Seller\Controllers\Api\Products\BrandController::class, 'index']);
	Route::get('approved', [\App\Http\Modules\Seller\Controllers\Api\Products\BrandController::class, 'show']);
	Route::get('all', [\App\Http\Modules\Seller\Controllers\Api\Products\ApprovedBrandController::class, 'index']);
	Route::post('approval', [\App\Http\Modules\Seller\Controllers\Api\Products\BrandController::class, 'store']);
});

Route::prefix('announcements')->group(function () {
	Route::get(Str::Empty, [\App\Http\Modules\Seller\Controllers\Api\Announcements\AnnouncementController::class, 'index'])->middleware(AUTH_SELLER);
	Route::put('{id}/mark', [\App\Http\Modules\Seller\Controllers\Api\Announcements\AnnouncementController::class, 'mark'])->middleware(AUTH_SELLER);
});

Route::prefix('support')->group(static function () {
	Route::prefix('tickets')->group(static function () {
		Route::get(Str::Empty, [\App\Http\Modules\Seller\Controllers\Api\Support\SupportController::class, 'index'])->middleware(AUTH_SELLER);
		Route::post(Str::Empty, [\App\Http\Modules\Seller\Controllers\Api\Support\SupportController::class, 'store'])->middleware(AUTH_SELLER);
	});
});

Route::prefix('payments')->group(static function () {
	Route::get('overview', [\App\Http\Modules\Seller\Controllers\Api\Payments\PaymentController::class, 'index']);
	Route::get('previous', [\App\Http\Modules\Seller\Controllers\Api\Payments\HistoryController::class, 'index']);
	Route::get('transactions', [\App\Http\Modules\Seller\Controllers\Api\Payments\TransactionController::class, 'index']);
});

Route::prefix('growth')->group(static function () {
	Route::get('overview', [\App\Http\Modules\Seller\Controllers\Api\Growth\OverviewController::class, 'show'])->middleware(AUTH_SELLER);
});

Route::prefix('promotions')->middleware(AUTH_SELLER)->group(static function () {
	Route::get(Str::Empty, [\App\Http\Modules\Seller\Controllers\Api\Advertisements\AdvertisementController::class, 'index']);
	Route::post(Str::Empty, [\App\Http\Modules\Seller\Controllers\Api\Advertisements\AdvertisementController::class, 'store']);
	Route::get('{advertisement}', [\App\Http\Modules\Seller\Controllers\Api\Advertisements\AdvertisementController::class, 'show']);
	Route::post('{advertisement}/update', [\App\Http\Modules\Seller\Controllers\Api\Advertisements\AdvertisementController::class, 'update']);
	Route::delete('{advertisement}', [\App\Http\Modules\Seller\Controllers\Api\Advertisements\AdvertisementController::class, 'delete']);
});

Route::prefix('bulk')->group(static function () {
	Route::get(Str::Empty, [\App\Http\Modules\Seller\Controllers\Api\Products\BulkTemplateController::class, 'show']);
	Route::post(Str::Empty, [\App\Http\Modules\Seller\Controllers\Api\Products\BulkProductController::class, 'store'])->middleware(AUTH_SELLER);
	Route::post('images', [\App\Http\Modules\Seller\Controllers\Api\Products\BulkImageController::class, 'store']);
});

Route::prefix('manifest')->group(static function () {
	Route::get('download', [\App\Http\Modules\Seller\Controllers\Api\Manifest\ManifestController::class, 'update']);
});

Route::prefix('dashboard')->group(static function () {
	Route::get(Str::Empty, [\App\Http\Modules\Seller\Controllers\Api\Dashboard\DashboardController::class, 'index'])->middleware(AUTH_SELLER);
});

Route::prefix('returns')->group(function () {
	Route::get(Str::Empty, [\App\Http\Modules\Seller\Controllers\Api\Orders\Returns\ReturnController::class, 'index']);
	Route::prefix('{return}')->group(function () {
		Route::post('approve', [\App\Http\Modules\Seller\Controllers\Api\Orders\Returns\ReturnController::class, 'approve']);
		Route::post('disapprove', [\App\Http\Modules\Seller\Controllers\Api\Orders\Returns\ReturnController::class, 'disapprove']);
	});
});