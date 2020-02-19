<?php

namespace App\Classes\Sorting;

class PriceDescending implements SortingAlgorithm {
	public static function obtain() {
		return ['originalPrice', 'desc'];
	}
}