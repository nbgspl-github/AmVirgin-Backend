<?php

namespace App\Classes\Sorting;

use App\Queries\ProductQuery;

class DiscountDescending implements Sorts{
	public static function sort(ProductQuery $query): ProductQuery{
		return $query->orderByDescending('discount');
	}

	public static function yeah(){
		echo "Yeah!";
	}
}