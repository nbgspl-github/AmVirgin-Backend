<?php

namespace App\Classes\Sorting;

class PriceAscending implements SortingAlgorithm {
	public static function obtain() {
		return ['originalPrice', 'asc'];
	}
}