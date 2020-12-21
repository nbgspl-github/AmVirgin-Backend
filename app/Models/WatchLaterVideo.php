<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatchLaterVideo extends Model
{
	protected $fillable = [
		'customer_id',
		'video_id',
		'video_type',
		'customer_type',
		'customer_ip',
		'customer_user_agent',
		'video_count',
	];


	public function video ()
	{
		return $this->belongsTo('App\Models\Video', 'video_id');
	}

}