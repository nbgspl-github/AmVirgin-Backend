<?php

namespace App\Filters;

use App\Resources\Shop\Customer\Catalog\Filters\BrandResource;

trait BrandFilter{
	public function brand(int ...$brandId): self{
		$column = defined(BrandResource::COLUMN) ? BrandResource::COLUMN : 'brandId';
		$this->query->whereIn($column, $brandId);
		return $this;
	}
}