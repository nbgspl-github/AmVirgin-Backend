<?php

namespace App\Models;

use App\Classes\Arrays;
use App\Constants\Constants;
use App\Storage\SecuredDisk;
use App\Traits\ActiveStatus;
use App\Traits\HasAttributeMethods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Slider extends Model{
	use HasAttributeMethods;
	protected $table = 'sliders';
	protected $fillable = [
		'title',
		'description',
		'banner',
		'type',
		'target',
		'rating',
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

	public static function active(): Builder{
		return self::where('active', true);
	}

	public function getBannerAttribute(): string{
		return SecuredDisk::existsUrl($this->attributes['target']);
	}
}