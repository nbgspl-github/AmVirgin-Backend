<?php

namespace App\Classes\Sorting;

use App\Models\Product;

class Popularity implements SortingAlgorithm{
	public static function obtain(){
		return ['id', 'asc'];
	}
}