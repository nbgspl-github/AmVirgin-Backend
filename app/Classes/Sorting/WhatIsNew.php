<?php

namespace App\Classes\Sorting;

use App\Models\Product;
use App\Queries\ProductQuery;

class WhatIsNew implements Sorts{
	public static function sort(ProductQuery $query): ProductQuery{
		return $query->latest();
	}
}