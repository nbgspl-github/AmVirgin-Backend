<?php

namespace App\Contracts;

use App\Queries\QueryProvider;
use Illuminate\Database\Eloquent\Builder;

interface DisplayableModel{
	/**
	 * Defines a set of conditions which decide whether a particular
	 * model instance is eligible to be displayed on the front-end.
	 * @return QueryProvider
	 */
	public static function displayable(): QueryProvider;
}