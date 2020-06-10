<?php

namespace App\Traits;

use App\Exceptions\QueryProviderNotSpecifiedException;
use App\Queries\AbstractQuery;

trait QueryProvider{
	/**
	 * Returns an instance of QueryProvider bound for this model.
	 * @return AbstractQuery
	 */
	public static abstract function startQuery(): AbstractQuery;
}