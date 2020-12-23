<?php

namespace App\Models\Video;

class WatchLater extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'video_watch_later';

	public function video () : \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(Video::class, 'video_id');
	}
}