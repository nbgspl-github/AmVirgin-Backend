<?php

namespace App\Classes\Sorting;

use App\Queries\ProductQuery;

class DiscountAscending implements Sorts{
	public static function sort(ProductQuery $query): ProductQuery{
		return $query->orderByAscending('discount');
	}
}