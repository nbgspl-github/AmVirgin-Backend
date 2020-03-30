<?php

namespace App\Models;

use App\Classes\Arrays;
use App\Constants\Constants;
use App\Contracts\DisplayableModel;
use App\Queries\SliderQuery;
use App\Storage\SecuredDisk;
use App\Traits\ActiveStatus;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model{
	use DynamicAttributeNamedMethods;
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

	public function getBannerAttribute(): string{
		return SecuredDisk::existsUrl($this->attributes['banner']);
	}

	public static function whereQuery(): SliderQuery{
		return SliderQuery::new();
	}
}