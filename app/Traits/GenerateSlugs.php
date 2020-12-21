<?php

namespace App\Traits;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

trait GenerateSlugs
{
	use HasSlug;

	public static function findBySlug ($slug)
	{
		return self::where('slug', $slug)->first();
	}

	public static function findOrFailBySlug ($slug)
	{
		return self::where('slug', $slug)->firstOrFail();
	}

	public function getSlugOptions () : SlugOptions
	{
		return SlugOptions::create()->saveSlugsTo('slug')->generateSlugsFrom('name');
	}
}