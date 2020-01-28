<?php

namespace App\Traits;

use Spatie\Sluggable\HasSlug;

trait Sluggable{
	use HasSlug;

	public static function findBySlug($slug){
		return self::where('slug', $slug)->first();
	}

	public static function findOrFailBySlug($slug){
		return self::where('slug', $slug)->firstOrFail();
	}
}