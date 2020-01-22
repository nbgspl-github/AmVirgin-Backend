<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoSnap extends Model{
	protected $table = 'video-snap';
	protected $fillable = [
		'videoId',
		'file',
		'description',
	];
}