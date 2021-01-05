<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register ()
	{
		\App\Models\Video\Video::observe(\App\Http\Modules\Admin\Observers\Videos\VideoObserver::class);
		\App\Models\Video\Source::observe(\App\Http\Modules\Admin\Observers\Videos\SourceObserver::class);
		\App\Models\Video\Snap::observe(\App\Http\Modules\Admin\Observers\Videos\SnapObserver::class);
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot ()
	{
		//
	}
}