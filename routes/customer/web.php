<?php

use App\Http\Modules\Customer\Controllers\Api\Auth\PasswordResetController;
use App\Http\Modules\Customer\Controllers\Web\WebLinksController;

\Illuminate\Support\Facades\Route::get('faq', [WebLinksController::class, 'faq']);
\Illuminate\Support\Facades\Route::get('about-us', [WebLinksController::class, 'aboutUs']);
\Illuminate\Support\Facades\Route::get('terms-conditions', [WebLinksController::class, 'termsAndConditions']);
\Illuminate\Support\Facades\Route::get('privacy-policy', [WebLinksController::class, 'privacyPolicy']);
\Illuminate\Support\Facades\Route::get('shipping', [WebLinksController::class, 'shipping']);
\Illuminate\Support\Facades\Route::get('cancellation', [WebLinksController::class, 'cancellation']);
\Illuminate\Support\Facades\Route::get('returns', [WebLinksController::class, 'returns']);
\Illuminate\Support\Facades\Route::get('password/reset', [PasswordResetController::class, 'reset'])->name('customer.password.reset');
\Illuminate\Support\Facades\Route::post('password/reset', [PasswordResetController::class, 'submit'])->name('customer.password.submit');
