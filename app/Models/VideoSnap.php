<?php

namespace App\Models;

use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class VideoSnap extends Model{
	use RetrieveResource;
	use RetrieveCollection;

	protected $table = 'video-snapshots';
	protected $fillable = [
		'videoId',
		'file',
		'description',
	];
}