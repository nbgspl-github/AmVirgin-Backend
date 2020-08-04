<?php

use App\Classes\Str;
use App\Http\Controllers\App\Seller\Attributes\ListController;
use App\Http\Controllers\App\Seller\Attributes\ProductAttributeController;
use App\Http\Controllers\App\Seller\Attributes\ValueController;
use App\Http\Controllers\App\Seller\AuthController;
use App\Http\Controllers\App\Seller\CategoryController;
use App\Http\Controllers\App\Seller\CityController;
use App\Http\Controllers\App\Seller\CountryController;
use App\Http\Controllers\App\Seller\CurrencyController;
use App\Http\Controllers\App\Seller\HsnCodeController;
use App\Http\Controllers\App\Seller\OrderController;
use App\Http\Controllers\App\Seller\ProductImageController;
use App\Http\Controllers\App\Seller\Products\ProductController;
use App\Http\Controllers\App\Seller\StateController;
use App\Http\Controllers\App\Seller\TwoFactorAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix(Str::Empty)->group(static function () {
    Route::get(Str::Empty, [TwoFactorAuthController::class, 'exists']);
    Route::post('login', [TwoFactorAuthController::class, 'login']);
    Route::post('register', [TwoFactorAuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware(AuthSeller);

    Route::prefix('profile')->group(static function () {
        Route::get(Str::Empty, [AuthController::class, 'profile'])->middleware(AuthSeller);
        Route::put(Str::Empty, [AuthController::class, 'updateProfile'])->middleware(AuthSeller);
        Route::post('avatar', [AuthController::class, 'updateAvatar'])->middleware(AuthSeller);
        Route::put('password', [AuthController::class, 'updatePassword'])->middleware(AuthSeller);

        Route::prefix('business-details')->middleware(AuthSeller)->group(static function () {
            Route::get(Str::Empty, [\App\Http\Controllers\App\Seller\BusinessDetailController::class, 'show']);
            Route::post(Str::Empty, [\App\Http\Controllers\App\Seller\BusinessDetailController::class, 'update']);
        });

        Route::prefix('bank-details')->middleware(AuthSeller)->group(static function () {
            Route::get(Str::Empty, [\App\Http\Controllers\App\Seller\BankDetailController::class, 'show']);
            Route::post(Str::Empty, [\App\Http\Controllers\App\Seller\BankDetailController::class, 'update']);
        });

        Route::prefix('contact-details')->group(static function () {

        });

        Route::prefix('pickup-details')->group(static function () {

        });

        Route::prefix('mou')->group(static function () {
            Route::get(Str::Empty, [\App\Http\Controllers\App\Seller\AgreementController::class, 'show']);
            Route::get('status', [\App\Http\Controllers\App\Seller\AgreementController::class, 'index'])->middleware(AuthSeller);
            Route::put(Str::Empty, [\App\Http\Controllers\App\Seller\AgreementController::class, 'update'])->middleware(AuthSeller);
        });
    });
});
Route::post('change-password', [AuthController::class, 'changePassword'])->middleware(AuthSeller);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('change-email', [AuthController::class, 'changeEmail'])->middleware(AuthSeller);
Route::post('change-email-token', [AuthController::class, 'getChangeEmailToken'])->middleware(AuthSeller);
Route::post('reset-password-token', [AuthController::class, 'getResetPasswordToken']);

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('seller.categories.index');
    Route::get('/{id}/attributes', [ListController::class, 'show'])->name('seller.categories.attributes.index');
});

Route::prefix('attributes')->group(function () {
    Route::get('/{attributeId}/values', [ValueController::class, 'show']);
});

Route::middleware(AuthSeller)->prefix('products')->group(function () {
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
        Route::post('upload', [\App\Http\Controllers\App\Seller\Products\ProductTrailerController::class, 'store']);
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
    Route::get(Str::Empty, [OrderController::class, 'index']);
    Route::get('{id}', [OrderController::class, 'show']);
    Route::get('download-pdf/{id}', [OrderController::class, 'orderDetails']);
    Route::put('status', [OrderController::class, 'updateStatusBulk']);
    Route::put('status/batch-update', [OrderController::class, 'updateStatusBulk']);
});
Route::prefix('order')->middleware('auth:seller-api')->group(function () {
    Route::get('/status', [OrderController::class, 'getOrderStatus']);
});

Route::prefix('orders-by-status')->middleware('auth:seller-api')->group(function () {
    Route::get('/{param}', [OrderController::class, 'getOrderByStatus']);
});

Route::prefix('customer')->middleware('auth:seller-api')->group(function () {
    Route::get('{param}', [OrderController::class, 'customer']);
});

Route::prefix('hsn')->group(function () {
    Route::get('/', [HsnCodeController::class, 'index']);
});

Route::prefix('brands')->middleware('auth:seller-api')->group(function () {
    Route::get(Str::Empty, [\App\Http\Controllers\App\Seller\BrandController::class, 'index']);
    Route::get('approved', [\App\Http\Controllers\App\Seller\BrandController::class, 'show']);
    Route::post('approval', [\App\Http\Controllers\App\Seller\BrandController::class, 'store']);
});

Route::prefix('announcements')->group(function () {
    Route::get(Str::Empty, [\App\Http\Controllers\App\Seller\AnnouncementController::class, 'index'])->middleware(AuthSeller);
    Route::put('{id}/mark', [\App\Http\Controllers\App\Seller\AnnouncementController::class, 'mark'])->middleware(AuthSeller);
});

Route::prefix('support')->group(static function () {
    Route::prefix('tickets')->group(static function () {
        Route::get(Str::Empty, [\App\Http\Controllers\App\Seller\SupportController::class, 'index'])->middleware(AuthSeller);
        Route::post(Str::Empty, [\App\Http\Controllers\App\Seller\SupportController::class, 'store'])->middleware(AuthSeller);
    });
});

Route::prefix('payments')->group(static function () {
    Route::get('overview', [\App\Http\Controllers\App\Seller\Payments\OverviewController::class, 'show'])->middleware(AuthSeller);
    Route::get('transaction', [OrderController::class, 'getPaymentsTransaction'])->middleware(AuthSeller);
    Route::get('previous', [OrderController::class, 'getPreviousPayments'])->middleware(AuthSeller);
});

Route::prefix('sales')->group(static function () {
    Route::get(Str::Empty, [\App\Http\Controllers\App\Seller\Payments\OverviewController::class, 'totalSales'])->middleware(AuthSeller);
});

Route::prefix('growth')->group(static function () {
    Route::get('overview', [\App\Http\Controllers\App\Seller\Growth\OverviewController::class, 'show'])->middleware(AuthSeller);
});

Route::prefix('promotions')->group(static function () {
    Route::get(Str::Empty, [\App\Http\Controllers\App\Seller\AdvertisementController::class, 'index'])->middleware(AuthSeller);
    Route::post(Str::Empty, [\App\Http\Controllers\App\Seller\AdvertisementController::class, 'store'])->middleware(AuthSeller);
    Route::get('{id}', [\App\Http\Controllers\App\Seller\AdvertisementController::class, 'show'])->middleware(AuthSeller);
});

Route::prefix('bulk')->group(static function () {
    Route::get(Str::Empty, [\App\Http\Controllers\App\Seller\Products\BulkTemplateController::class, 'show']);
    Route::post(Str::Empty, [\App\Http\Controllers\App\Seller\Products\BulkProductController::class, 'store']);
    Route::post('images', [\App\Http\Controllers\App\Seller\Products\BulkImageController::class, 'store']);
});

Route::prefix('manifest')->group(static function () {
    Route::get('download', [\App\Http\Controllers\App\Seller\Manifest\ManifestController::class, 'show']);
});