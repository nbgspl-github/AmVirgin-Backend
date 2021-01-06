<?php
\Illuminate\Support\Facades\Route::get('/trigger', function () {
	\App\Jobs\DistressJob::dispatch();
});