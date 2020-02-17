<?php

namespace App\Classes\Sorting;

use App\Models\Product;

class NewlyAdded implements SortingAlgorithm{
	public static function obtain(){
		return ['id', 'desc'];
	}
}