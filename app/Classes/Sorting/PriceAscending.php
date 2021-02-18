<?php

namespace App\Classes\Sorting;

use App\Queries\ProductQuery;

class PriceAscending implements Sorts{
	public static function sort(ProductQuery $query): ProductQuery{
		return $query->orderByAscending('sellingPrice');
	}
}