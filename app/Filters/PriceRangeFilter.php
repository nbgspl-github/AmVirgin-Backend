<?php

namespace App\Filters;

use App\Resources\Shop\Customer\Catalog\Filters\PriceRangeResource;

trait PriceRangeFilter{
	public function priceRange(array ...$ranges): self{
		$column = defined(PriceRangeResource::RequiredColumn) ? PriceRangeResource::RequiredColumn : 'price';
		foreach ($ranges as $range)
			$this->query->whereBetween($column, $range['upper'], $range['lower']);
		return $this;
	}
}