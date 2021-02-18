<?php

namespace App\Models\Video;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Source extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'video_sources';

	public function setFileAttribute ($value)
	{
		$this->attributes['file'] = $this->storeWhenUploadedCorrectly('videos/video_tracks', $value);
	}

	public function getFileAttribute () : ?string
	{
		return $this->retrieveMedia($this->attributes['file']);
	}

	public function language () : BelongsTo
	{
		return $this->belongsTo(\App\Models\Video\Language::class, 'video_language_id');
	}

	public function audios () : \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(\App\Models\Models\Video\Audio::class, 'video_source_id');
	}

	public function audioLanguages () : \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
		return $this->belongsToMany(Language::class, 'video_audios', 'video_source_id', 'video_language_id');
	}

	public function subtitles () : \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(\App\Models\Models\Video\Subtitle::class, 'video_source_id');
	}

	public function subtitleLanguages () : \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
		return $this->belongsToMany(Language::class, 'video_subtitles', 'video_source_id', 'video_language_id');
	}

	public function queues () : HasMany
	{
		return $this->hasMany(Queue::class, 'video_source_id');
	}
}