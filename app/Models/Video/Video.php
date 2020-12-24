<?php

namespace App\Models\Video;

use App\Queries\VideoQuery;
use App\Traits\ActiveStatus;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\GenerateSlugs;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Spatie\Sluggable\SlugOptions;

class Video extends \App\Library\Database\Eloquent\Model
{
	use GenerateSlugs, SoftDeletes;

	protected $table = 'videos';

	public function setQualitySlug (Collection $mediaQualities) : Video
	{
		$mediaQualities = $mediaQualities->unique('name');
		$mediaQualities->transform(function (\App\Models\Video\MediaQuality $quality) {
			return $quality->getName();
		});
		$this->qualitySlug = implode('/', $mediaQualities->toArray());
		return $this;
	}

	public function setLanguageSlug (Collection $mediaLanguages) : Video
	{
		$mediaLanguages = $mediaLanguages->unique('name');
		$mediaLanguages->transform(function (\App\Models\Video\MediaLanguage $language) {
			return $language->getName();
		});
		$this->languageSlug = implode('/', $mediaLanguages->toArray());
		return $this;
	}

	public function genre () : BelongsTo
	{
		return $this->belongsTo(\App\Models\Video\Genre::class, 'genreId');
	}

	public function sources () : HasMany
	{
		return $this->hasMany(\App\Models\Video\Source::class, 'videoId');
	}

	public function snaps () : HasMany
	{
		return $this->hasMany(\App\Models\Video\Snap::class, 'videoId');
	}

	public function getSlugOptions () : SlugOptions
	{
		return SlugOptions::create()
			->generateSlugsFrom('title')
			->saveSlugsTo('slug');
	}

	public static function startQuery () : VideoQuery
	{
		return VideoQuery::begin();
	}
}