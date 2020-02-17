<?php

namespace App\Classes\Sorting;

use App\Models\Product;

class PriceAscending implements SortingAlgorithm{
	public static function obtain(){
		return ['price', 'asc'];
	}
}