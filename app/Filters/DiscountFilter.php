<?php

namespace App\Filters;

use App\Classes\Arrays;
use App\Resources\Shop\Customer\Catalog\Filters\DiscountResource;

trait DiscountFilter{
	public function discount(int $discount): self{
		$column = defined(DiscountResource::RequiredColumn) ? DiscountResource::RequiredColumn : 'discount';
		$this->query->where($column, '>=', $discount);
		return $this;
	}
}