<?php

namespace App\Resources\Shop\Customer\Catalog\Filters;

use Illuminate\Support\Collection;

class BrandResource extends AbstractBuiltInResource
{
	const COLUMN = 'brandId';
	const KEY = 'filter_brand';
	const TYPE = 'brand';
	const MODE = 'multiple';
	const LABEL = 'Brand';

	public function withValues (Collection $values) : self
	{
		$this->values = $values->pluck(['brandId'])->toArray();
		return $this;
	}
}