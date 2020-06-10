<?php

namespace App\Classes\Sorting;

use App\Queries\ProductQuery;

class Popularity implements Sorts{
	public static function sort(ProductQuery $query): ProductQuery{
		return $query->orderByDescending('hits');
	}
}