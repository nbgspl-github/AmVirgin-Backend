<?php

namespace App\Models;

use App\Library\Utils\Uploads;
use App\Queries\SliderQuery;
use App\Traits\ActiveStatus;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Slider extends Model
{
	use DynamicAttributeNamedMethods;
	use ActiveStatus;

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
		'id',
		'created_at',
		'updated_at',
	];
	protected $casts = [
		'rating' => 'int',
		'active' => 'bool',
	];
	public const TargetType = [
		'ExternalLink' => 'external-link',
		'VideoKey' => 'video-key',
		'ProductKey' => 'product-key',
	];

	public function getBannerAttribute () : ?string
	{
		return Uploads::existsUrl($this->attributes['banner']);
	}

	public function video () : HasOne
	{
		return $this->hasOne(Video::class, 'id', 'target');
	}

	public function product () : HasOne
	{
		return $this->hasOne(Product::class, 'target');
	}

	public static function startQuery () : SliderQuery
	{
		return SliderQuery::begin();
	}
}