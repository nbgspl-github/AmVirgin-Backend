<?php

namespace App\Models\Models\Video;

class Subtitle extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'video_subtitles';

	public function setFileAttribute ($value)
	{
		$this->attributes['file'] = $this->storeWhenUploadedCorrectly('videos/subtitle_tracks', $value);
	}

	public function getFileAttribute () : ?string
	{
		return $this->retrieveMedia($this->attributes['file']);
	}

	public function language () : \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(\App\Models\Video\Language::class, 'video_language_id');
	}
}