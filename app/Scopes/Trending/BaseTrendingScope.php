<?php

namespace App\Scopes\Trending;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BaseTrendingScope implements Scope
{
	public function apply (Builder $builder, Model $model)
	{

	}

	/**
	 * Extend the query builder with the needed functions.
	 *
	 * @param Builder $builder
	 */
	public function extend (Builder $builder)
	{
		$builder->macro('withYouths', function (Builder $builder) {
			return $builder->withoutGlobalScope($this);
		});
	}
}