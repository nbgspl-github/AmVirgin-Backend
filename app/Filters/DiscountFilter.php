<?php

namespace App\Filters;

use App\Resources\Shop\Customer\Catalog\Filters\DiscountResource;

trait DiscountFilter{
	public function discount(int $discount): self{
		$column = defined(DiscountResource::COLUMN) ? DiscountResource::COLUMN : 'discount';
		$this->query->where($column, '>=', $discount);
		return $this;
	}
}