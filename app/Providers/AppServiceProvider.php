<?php

namespace App\Providers;

use App\Classes\Str;
use Illuminate\Routing\Exceptions\UrlGenerationException;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register(){
		//
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot(){
		Schema::defaultStringLength(256);
		Blade::directive('required', function ($name){
			return "$name<span class=\"text-muted\">*</span>";
		});
	}
}