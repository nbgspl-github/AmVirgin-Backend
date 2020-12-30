<?php

namespace App\Models\Video;

class WatchList extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'video_watchlist';

	public function video () : \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(Video::class, 'video_id');
	}

	public function customer () : \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(\App\Models\Auth\Customer::class, 'customer_id');
	}
}