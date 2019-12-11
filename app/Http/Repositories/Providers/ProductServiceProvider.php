<?php

namespace App\Http\Repositories\Providers;

use App\Http\Repositories\Contracts\ProductRepositoryInterface;
use App\Http\Repositories\Eloquent\ProductRepository;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider{
	public function register(){
		$this->app->bind(
			ProductRepositoryInterface::class, ProductRepository::class
		);
	}
}