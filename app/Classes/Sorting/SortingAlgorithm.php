<?php

namespace App\Classes\Sorting;

use App\Models\Product;

interface SortingAlgorithm{
	/**
	 * @return callable|array
	 */
	public static function obtain();
}