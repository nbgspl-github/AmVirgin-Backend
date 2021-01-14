<?php

namespace App\Filters;

use App\Resources\Shop\Customer\Catalog\Filters\GenderResource;

trait GenderFilter{
	public function gender(string $gender): self{
		$column = defined(GenderResource::COLUMN) ? GenderResource::COLUMN : 'gender';
		$this->query->where($column, $gender);
		return $this;
	}
}