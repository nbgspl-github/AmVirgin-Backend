<?php

namespace App\Filters;

use App\Classes\Arrays;
use App\Resources\Shop\Customer\Catalog\Filters\PriceRangeResource;
use Illuminate\Support\Collection;

trait PriceRangeFilter{
	public function priceRange(array ...$ranges): self{
		$column = defined(PriceRangeResource::RequiredColumn) ? PriceRangeResource::RequiredColumn : 'price';
		foreach ($ranges as $range)
			$this->query->whereBetween($column, $range['upper'], $range['lower']);
		return $this;
	}
}