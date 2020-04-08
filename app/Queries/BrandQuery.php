<?php

namespace App\Queries;

use App\Models\Brand;

class BrandQuery extends AbstractQuery{

	protected function model(): string{
		return Brand::class;
	}

	public static function begin(): self{
		return new self();
	}

	public function displayable(): self{
		return $this;
	}
}