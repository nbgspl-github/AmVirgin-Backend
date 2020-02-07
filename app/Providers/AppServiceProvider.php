<?php

namespace App\Providers;

use App\Classes\Str;
use App\Storage\SecuredDisk;
use Illuminate\Support\Facades\Blade;
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
			return "$name<span class='text-primary'>*</span>";
		});

		Blade::directive('img', function ($path, $attributes = [], $accessor = SecuredDisk::class){
			if (!$accessor::access()->exists($path)) {
				return Str::Empty;
			}
			$rendered = Str::Empty;
			$collection = collect($attributes);
			if ($collection->count() > 0) {
				$collection->each(function ($item) use ($rendered){
					$rendered .= sprintf("%s = \"%s\" ", $item['key'], $item['value']);
				});
			}
			return sprintf("<img src=\"%s\" %s/>", $accessor->url($path), $rendered);
		});
	}
}