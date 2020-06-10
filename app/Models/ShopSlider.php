<?php

namespace App\Models;

use App\Queries\ShopSliderQuery;
use App\Traits\ActiveStatus;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class ShopSlider extends Model{
	use ActiveStatus;
	use RetrieveResource;
	use DynamicAttributeNamedMethods;
	protected $table = 'shop-sliders';
	protected $fillable = [
		'title',
		'description',
		'banner',
		'target',
		'rating',
		'active',
	];
	protected $hidden = [
		'id',
		'created_at',
		'updated_at',
	];

	public static function whereQuery(): ShopSliderQuery{
		return ShopSliderQuery::begin();
	}
}