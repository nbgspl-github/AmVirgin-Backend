<?php

namespace App\Models;

use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class WatchLaterVideo extends Model {
	use RetrieveResource;
	// protected $table = '';
	protected $fillable = [
		'customer_id',
		'video_id',
		'video_type',
		'customer_type',
		'customer_ip',
		'customer_user_agent',
		'video_count', 
	];
	 

	// public function state() {
	// 	return $this->belongsTo('App\Models\State', 'stateId');
	// }

	// public function city() {
	// 	return $this->belongsTo('App\Models\City', 'cityId');
	// }
}