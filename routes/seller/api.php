<?php

use App\Http\Modules\Seller\Controllers\Api\Advertisements\AdvertisementController;
use App\Http\Modules\Seller\Controllers\Api\Agreements\AgreementController;
use App\Http\Modules\Seller\Controllers\Api\Announcements\AnnouncementController;
use App\Http\Modules\Seller\Controllers\Api\Attributes\ListController;
use App\Http\Modules\Seller\Controllers\Api\Attributes\ProductAttributeController;
use App\Http\Modules\Seller\Controllers\Api\Attributes\ValueController;
use App\Http\Modules\Seller\Controllers\Api\Auth\AuthController;
use App\Http\Modules\Seller\Controllers\Api\Auth\AvatarController;
use App\Http\Modules\Seller\Controllers\Api\Auth\BankDetailController;
use App\Http\Modules\Seller\Controllers\Api\Auth\BusinessDetailController;
use App\Http\Modules\Seller\Controllers\Api\Auth\ExistenceController;
use App\Http\Modules\Seller\Controllers\Api\Auth\LoginController;
use App\Http\Modules\Seller\Controllers\Api\Auth\PasswordController;
use App\Http\Modules\Seller\Controllers\Api\Auth\ProfileController;
use App\Http\Modules\Seller\Controllers\Api\Auth\RegisterController;
use App\Http\Modules\Seller\Controllers\Api\Dashboard\DashboardController;
use App\Http\Modules\Seller\Controllers\Api\Growth\OverviewController;
use App\Http\Modules\Seller\Controllers\Api\Manifest\ManifestController;
use App\Http\Modules\Seller\Controllers\Api\Orders\OrderController;
use App\Http\Modules\Seller\Controllers\Api\Orders\Returns\ReturnController;
use App\Http\Modules\Seller\Controllers\Api\Orders\Status\ActionController;
use App\Http\Modules\Seller\Controllers\Api\Orders\Status\BulkActionController;
use App\Http\Modules\Seller\Controllers\Api\Payments\HistoryController;
use App\Http\Modules\Seller\Controllers\Api\Payments\PaymentController;
use App\Http\Modules\Seller\Controllers\Api\Payments\TransactionController;
use App\Http\Modules\Seller\Controllers\Api\Products\ApprovedBrandController;
use App\Http\Modules\Seller\Controllers\Api\Products\BrandController;
use App\Http\Modules\Seller\Controllers\Api\Products\BulkImageController;
use App\Http\Modules\Seller\Controllers\Api\Products\BulkProductController;
use App\Http\Modules\Seller\Controllers\Api\Products\BulkTemplateController;
use App\Http\Modules\Seller\Controllers\Api\Products\CategoryController;
use App\Http\Modules\Seller\Controllers\Api\Products\HsnCodeController;
use App\Http\Modules\Seller\Controllers\Api\Products\ProductController;
use App\Http\Modules\Seller\Controllers\Api\Products\ProductImageController;
use App\Http\Modules\Seller\Controllers\Api\Products\ProductTrailerController;
use App\Http\Modules\Seller\Controllers\Api\Shared\CityController;
use App\Http\Modules\Seller\Controllers\Api\Shared\CountryController;
use App\Http\Modules\Seller\Controllers\Api\Shared\CurrencyController;
use App\Http\Modules\Seller\Controllers\Api\Shared\StateController;
use App\Http\Modules\Seller\Controllers\Api\Support\SupportController;
use App\Library\Utils\Extensions\Str;
use Illuminate\Support\Facades\Route;

Route::get('exists', [ExistenceController::class, 'exists']);
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout']);
Route::post('register', [RegisterController::class, 'register']);

Route::prefix('profile')->group(function () {
	Route::get(Str::Empty, [ProfileController::class, 'show']);
	Route::put(Str::Empty, [ProfileController::class, 'update']);
	Route::post('avatar', [AvatarController::class, 'update']);
	Route::put('password', [PasswordController::class, 'update']);

	Route::prefix('business-details')->group(function () {
		Route::get(Str::Empty, [BusinessDetailController::class, 'show']);
		Route::post(Str::Empty, [BusinessDetailController::class, 'update']);
	});

	Route::prefix('bank-details')->group(function () {
		Route::get(Str::Empty, [BankDetailController::class, 'show']);
		Route::post(Str::Empty, [BankDetailController::class, 'update']);
	});

	Route::prefix('mou')->group(function () {
		Route::get(Str::Empty, [AgreementController::class, 'show']);
		Route::get('status', [AgreementController::class, 'index']);
		Route::put(Str::Empty, [AgreementController::class, 'update']);
	});
});
Route::post('change-password', [AuthController::class, 'changePassword']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('change-email', [AuthController::class, 'changeEmail']);
Route::post('change-email-token', [AuthController::class, 'getChangeEmailToken']);
Route::post('reset-password-token', [AuthController::class, 'getResetPasswordToken']);

Route::prefix('categories')->group(function () {
	Route::get(Str::Empty, [CategoryController::class, 'index']);
	Route::get('{category}/attributes', [ListController::class, 'show']);
});

Route::prefix('attributes')->group(function () {
	Route::get('{attributeId}/values', [ValueController::class, 'show']);
});

Route::prefix('products')->group(function () {
	Route::get('/', [ProductController::class, 'index']);
	Route::post(Str::Empty, [ProductController::class, 'store']);
	Route::get('{id}', [ProductController::class, 'show']);
	Route::get('edit/{id}', [ProductController::class, 'edit']);
	Route::post('{id}', [ProductController::class, 'update']);
	Route::post('change-status/{id}', [ProductController::class, 'changeStatus']);
	Route::delete('{id}', [ProductController::class, 'delete']);
	Route::delete('/images/{id}', [ProductImageController::class, 'delete']);
	Route::delete('/attributes/{id}', [ProductAttributeController::class, 'delete']);

	Route::prefix('token')->group(function () {
		Route::get('create', [ProductController::class, 'token']);
	});

	Route::prefix('trailer')->group(function () {
		Route::post('upload', [ProductTrailerController::class, 'store']);
	});
});

Route::prefix('currencies')->group(function () {
	Route::get('/', [CurrencyController::class, 'index']);
});

Route::prefix('countries')->group(function () {
	Route::get('/', [CountryController::class, 'index']);
	Route::get('{countryId}/states', [StateController::class, 'index']);
	Route::get('states/{stateId}/cities', [CityController::class, 'index']);
});

Route::prefix('orders')->group(function () {
	Route::get(Str::Empty, [OrderController::class, 'index']);
	Route::get('{order}', [OrderController::class, 'show']);
	Route::put('{order}/status', [ActionController::class, 'handle']);
	Route::put('status', [BulkActionController::class, 'handle']);
});

Route::prefix('hsn')->group(function () {
	Route::get('/', [HsnCodeController::class, 'index']);
});

Route::prefix('brands')->group(function () {
	Route::get(Str::Empty, [BrandController::class, 'index']);
	Route::get('all', [ApprovedBrandController::class, 'index']);
	Route::get('approved', [BrandController::class, 'show']);
	Route::post('approval', [BrandController::class, 'store']);
});

Route::prefix('announcements')->group(function () {
	Route::get(Str::Empty, [AnnouncementController::class, 'index']);
	Route::put('{id}/mark', [AnnouncementController::class, 'mark']);
});

Route::prefix('support')->group(function () {
	Route::prefix('tickets')->group(function () {
		Route::get(Str::Empty, [SupportController::class, 'index']);
		Route::post(Str::Empty, [SupportController::class, 'store']);
	});
});

Route::prefix('payments')->group(function () {
	Route::get('overview', [PaymentController::class, 'index']);
	Route::get('previous', [HistoryController::class, 'index']);
	Route::get('transactions', [TransactionController::class, 'index']);
});

Route::prefix('growth')->group(function () {
	Route::get('overview', [OverviewController::class, 'show']);
});

Route::prefix('promotions')->group(function () {
	Route::get(Str::Empty, [AdvertisementController::class, 'index']);
	Route::post(Str::Empty, [AdvertisementController::class, 'store']);
	Route::get('{advertisement}', [AdvertisementController::class, 'show']);
	Route::post('{advertisement}/update', [AdvertisementController::class, 'update']);
	Route::delete('{advertisement}', [AdvertisementController::class, 'delete']);
});

Route::prefix('bulk')->group(function () {
	Route::get(Str::Empty, [BulkTemplateController::class, 'show']);
	Route::post(Str::Empty, [BulkProductController::class, 'store']);
	Route::post('images', [BulkImageController::class, 'store']);
});

Route::prefix('manifest')->group(function () {
	Route::get('download', [ManifestController::class, 'update']);
});

Route::prefix('dashboard')->group(function () {
	Route::get(Str::Empty, [DashboardController::class, 'index']);
});

Route::prefix('returns')->group(function () {
	Route::get(Str::Empty, [ReturnController::class, 'index']);
	Route::prefix('{return}')->group(function () {
		Route::post('approve', [ReturnController::class, 'approve']);
		Route::post('disapprove', [ReturnController::class, 'disapprove']);
	});
});