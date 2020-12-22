<?php

namespace App\Models\Video;

class WatchLater extends \App\Library\Database\Eloquent\Model
{
	public function video () : \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(Video::class, 'video_id');
	}
}