<?php

namespace App\Models;

use App\Models\Video\Video;
use App\Queries\SliderQuery;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Slider extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;

	protected $table = 'sliders';
	protected $casts = [
		'rating' => 'int',
		'active' => 'bool',
	];
	public const TargetType = [
		'ExternalLink' => 'external-link',
		'VideoKey' => 'video-key',
		'ProductKey' => 'product-key',
	];

	public function setBannerAttribute ($value) : void
	{
		$this->attributes['banner'] = $this->storeWhenUploadedCorrectly('sliders/banners', $value);
	}

	public function getBannerAttribute ($value) : ?string
	{
		return $this->retrieveMedia($value);
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