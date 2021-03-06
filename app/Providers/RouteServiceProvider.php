<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
	protected $namespace = 'App\Http\Controllers';

	public function boot ()
	{
		parent::boot();
	}

	public function map ()
	{
		$this->mapAdminRoutes();
		$this->mapSellerRoutes();
		$this->mapCustomerRoutes();
	}

	protected function mapAdminRoutes ()
	{
		Route::prefix('admin')->middleware('web')->namespace($this->namespace)->group(base_path('routes/admin/web.php'));
		Route::middleware('web')->namespace($this->namespace)->group(base_path('routes/admin/web.php'));
	}

	protected function mapSellerRoutes ()
	{
		Route::prefix('api/seller')->middleware('api')->namespace($this->namespace)->group(base_path('routes/seller/api.php'));
	}

	protected function mapCustomerRoutes ()
	{
		Route::prefix('customer')->middleware('web')->namespace($this->namespace)->group(base_path('routes/customer/web.php'));
		Route::prefix('api/customer')->middleware('api')->namespace($this->namespace)->group(base_path('routes/customer/api.php'));
	}

	protected function mapExtraRoutes ()
	{
		Route::prefix('api')->middleware('api')->namespace($this->namespace)->group(base_path('routes/api.php'));
	}
}