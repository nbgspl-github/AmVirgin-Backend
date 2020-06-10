<?php

namespace App\Classes\Sorting;

use App\Models\Product;
use App\Queries\AbstractQuery;
use App\Queries\ProductQuery;
use Illuminate\Database\Eloquent\Builder;
use ReflectionClass;

abstract class AbstractSorts{
	public static function take($class): Sorts{
		$class = new ReflectionClass($class);
		return $class->newInstanceWithoutConstructor();
	}
}