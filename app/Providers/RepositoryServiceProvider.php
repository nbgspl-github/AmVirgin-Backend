<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register ()
	{
		$this->app->bind(
			\App\Http\Modules\Shared\Repository\RepositoryInterface::class,
			\App\Http\Modules\Shared\Repository\BaseRepository::class
		);
		$this->app->bind(
			\App\Http\Modules\Admin\Repository\Videos\VideoRepositoryInterface::class,
			\App\Http\Modules\Admin\Repository\Videos\VideoRepository::class
		);
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