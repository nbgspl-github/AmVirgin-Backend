<?php

namespace App\Classes\Sorting;

use App\Queries\AbstractQuery;
use App\Queries\ProductQuery;
use Illuminate\Database\Eloquent\Builder;

class Relevance implements Sorts{
	public static function sort(ProductQuery $query): ProductQuery{
		return $query->orderBy('id');
	}
}