<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider{
	protected $namespace = 'App\Http\Controllers';

	public function boot(){
		parent::boot();
	}

	public function map(){
		$this->mapAdminRoutes();
		$this->mapSellerRoutes();
		$this->mapCustomerRoutes();
	}

	protected function mapAdminRoutes(){
		Route::middleware('web')
			->namespace($this->namespace)
			->group(base_path('routes/admin/web.php'));

		Route::prefix('api')
			->middleware('api')
			->namespace($this->namespace)
			->group(base_path('routes/admin/api.php'));
	}

	protected function mapSellerRoutes(){
		Route::middleware('web')
			->namespace($this->namespace)
			->group(base_path('routes/seller/web.php'));

		Route::prefix('api')
			->middleware('api')
			->namespace($this->namespace)
			->group(base_path('routes/seller/api.php'));

		Route::prefix('api')
			->middleware('api')
			->namespace($this->namespace)
			->group(base_path('routes/seller/shop.php'));
	}

	protected function mapCustomerRoutes(){
		Route::middleware('web')
			->namespace($this->namespace)
			->group(base_path('routes/customer/web.php'));

		Route::prefix('api')
			->middleware('api')
			->namespace($this->namespace)
			->group(base_path('routes/customer/api.php'));
	}
}