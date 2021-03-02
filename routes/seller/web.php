<?php

use App\Http\Modules\Seller\Controllers\Web\WebLinksController;

\Illuminate\Support\Facades\Route::get('privacy-policy', [WebLinksController::class, 'privacyPolicy']);
\Illuminate\Support\Facades\Route::get('about-us', [WebLinksController::class, 'aboutUs']);
\Illuminate\Support\Facades\Route::get('terms-conditions', [WebLinksController::class, 'termsAndConditions']);