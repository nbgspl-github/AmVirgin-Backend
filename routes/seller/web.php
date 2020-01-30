<?php

use Illuminate\Support\Facades\Route;

Route::any('seller/{a?}/{b?}/{c?}/{d?}/{e?}/{f?}/{g?}/{h?}/{i?}/{j?}/{l?}/{m?}/{o?}/{p?}', [\App\Http\Controllers\Web\Seller\ReactController::class, 'index']);