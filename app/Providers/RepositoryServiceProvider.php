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
		$this->app->bind(
			\App\Http\Modules\Admin\Repository\User\Customer\Contracts\CustomerRepository::class,
			\App\Http\Modules\Admin\Repository\User\Customer\CustomerRepository::class
		);
		$this->app->bind(
			\App\Http\Modules\Admin\Repository\User\Seller\Contracts\SellerRepository::class,
			\App\Http\Modules\Admin\Repository\User\Seller\SellerRepository::class
		);
		$this->app->bind(
			\App\Http\Modules\Admin\Repository\Products\Contracts\ProductRepository::class,
			\App\Http\Modules\Admin\Repository\Products\ProductRepository::class
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