<?php

namespace App\Filters;

use App\Resources\Shop\Customer\Catalog\Filters\BrandResource;
use App\Resources\Shop\Customer\Catalog\Filters\CategoryResource;

trait CategoryFilter{
	public function category(int ...$categoryId): self{
		$column = defined(CategoryResource::RequiredColumn) ? CategoryResource::RequiredColumn : 'categoryId';
		$this->query->whereIn($column, $categoryId);
		return $this;
	}
}