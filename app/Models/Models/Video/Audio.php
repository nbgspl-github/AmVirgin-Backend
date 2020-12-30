<?php

namespace App\Models\Models\Video;

class Audio extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'video_audios';

	public function language () : \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(\App\Models\Video\Language::class, 'video_language_id');
	}
}