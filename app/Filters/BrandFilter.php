<?php

namespace App\Filters;

trait BrandFilter{
	public function brand(int ...$brandId): self{
		$column = defined(static::BrandColumnKey) ? static::BrandColumnKey : 'brandId';
		$this->query->whereIn($column, $brandId);
		return $this;
	}
}