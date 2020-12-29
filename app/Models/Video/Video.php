<?php

namespace App\Models\Video;

use App\Queries\VideoQuery;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends \App\Library\Database\Eloquent\Model
{
	use \App\Traits\MediaLinks;

	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $table = 'videos';

	public function getPosterAttribute () : ?string
	{
		return $this->retrieveMedia($this->attributes['poster']);
	}

	public function setPosterAttribute ($value)
	{
		$this->attributes['poster'] = $this->storeWhenUploadedCorrectly('posters', $value);
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

	public static function startQuery () : VideoQuery
	{
		return VideoQuery::begin();
	}
}