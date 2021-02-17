<?php

namespace App\Models\Video;

class Rental extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'rentals';

	public function video () : \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(Video::class, 'video_id');
	}
}