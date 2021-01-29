<?php

namespace App\Models;

use App\Queries\ShopSliderQuery;
use App\Traits\DynamicAttributeNamedMethods;

class ShopSlider extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;

	protected $table = 'shop-sliders';

	public static function whereQuery () : ShopSliderQuery
	{
		return ShopSliderQuery::begin();
	}
}