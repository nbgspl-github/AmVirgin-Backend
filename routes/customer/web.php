<?php
\Illuminate\Support\Facades\Route::get('privacy-policy', [\App\Http\Modules\Customer\Controllers\Web\WebLinksController::class, 'privacyPolicy']);
\Illuminate\Support\Facades\Route::get('about-us', [\App\Http\Modules\Customer\Controllers\Web\WebLinksController::class, 'aboutUs']);
\Illuminate\Support\Facades\Route::get('terms-conditions', [\App\Http\Modules\Customer\Controllers\Web\WebLinksController::class, 'termsAndConditions']);
\Illuminate\Support\Facades\Route::get('password/reset', [\App\Http\Modules\Customer\Controllers\Api\Auth\PasswordResetController::class, 'reset'])->name('customer.password.reset');
\Illuminate\Support\Facades\Route::post('password/reset', [\App\Http\Modules\Customer\Controllers\Api\Auth\PasswordResetController::class, 'submit'])->name('customer.password.submit');