<?php

namespace App\Classes\Sorting;

use App\Models\Product;
use App\Queries\AbstractQuery;
use App\Queries\ProductQuery;
use Illuminate\Database\Eloquent\Builder;

interface Sorts{
	public static function sort(ProductQuery $query): ProductQuery;
}