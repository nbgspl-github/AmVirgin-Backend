<?php

namespace App\Classes\Sorting;

use App\Queries\ProductQuery;

class PriceDescending implements Sorts{
	public static function sort(ProductQuery $query): ProductQuery{
		return $query->orderByDescending('originalPrice');
	}
}