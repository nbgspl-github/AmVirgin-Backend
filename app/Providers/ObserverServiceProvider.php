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