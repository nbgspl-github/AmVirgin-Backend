<?php

namespace App\Models;

use App\Constants\Constants;
use App\Traits\ActiveStatus;
use App\Traits\HasAttributeMethods;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model{
	use ActiveStatus, HasAttributeMethods;
	protected $table = 'sliders';
	protected $fillable = [
		'title',
		'description',
		'poster',
		'target',
		'stars',
		'active',
	];
	protected $hidden = [
		'created_at',
		'updated_at',
	];
	public const TargetType = [
		'ExternalLink' => 'external-link',
		'VideoKey' => 'video-key',
	];
}