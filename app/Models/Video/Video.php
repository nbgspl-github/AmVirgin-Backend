<?php

namespace App\Models\Video;

use App\Queries\VideoQuery;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Video
 * @package App\Models\Video
 * @property \App\Library\Enums\Videos\Types $type
 */
class Video extends \App\Library\Database\Eloquent\Model
{
	use \App\Traits\MediaLinks;

	use \Illuminate\Database\Eloquent\SoftDeletes;

	use \BenSampo\Enum\Traits\CastsEnums;

	protected $table = 'videos';

	protected $casts = [
		'sections' => 'array',
		'type' => \App\Library\Enums\Videos\Types::class
	];

	public function getPosterAttribute () : ?string
	{
		return $this->retrieveMedia($this->attributes['poster']);
	}

	public function setPosterAttribute ($value)
	{
		$this->attributes['poster'] = $this->storeWhenUploadedCorrectly('videos/posters', $value);
	}

	public function getBackdropAttribute () : ?string
	{
		return $this->retrieveMedia($this->attributes['backdrop']);
	}

	public function setBackdropAttribute ($value)
	{
		$this->attributes['backdrop'] = $this->storeWhenUploadedCorrectly('videos/backdrops', $value);
	}

	public function getTrailerAttribute () : ?string
	{
		return $this->retrieveMedia($this->attributes['trailer']);
	}

	public function setTrailerAttribute ($value)
	{
		$this->attributes['trailer'] = $this->storeWhenUploadedCorrectly('videos/trailers', $value);
	}

	public function genre () : BelongsTo
	{
		return $this->belongsTo(\App\Models\Video\Genre::class, 'genre_id');
	}

	public function sources () : HasMany
	{
		return $this->hasMany(\App\Models\Video\Source::class, 'video_id');
	}

	public function snaps () : HasMany
	{
		return $this->hasMany(\App\Models\Video\Snap::class, 'video_id');
	}

	public function audios () : HasMany
	{
		return $this->hasMany(\App\Models\Models\Video\Audio::class, 'video_id');
	}

	public function subtitles () : HasMany
	{
		return $this->hasMany(\App\Models\Models\Video\Subtitle::class, 'video_id');
	}

	public function queues () : HasMany
	{
		return $this->hasMany(Queue::class, 'video_id');
	}

	public function isTranscoding () : bool
	{
		return $this->queues()->whereIn('status', ['Encoding', 'Queued'])->whereNull('completed_at')->exists();
	}

	public static function startQuery () : VideoQuery
	{
		return VideoQuery::begin();
	}
}