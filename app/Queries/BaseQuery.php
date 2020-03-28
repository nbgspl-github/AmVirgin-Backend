<?php

namespace App\Queries;

abstract class BaseQuery{
	public static function products(): string{
		return new ProductQuery();
	}
}