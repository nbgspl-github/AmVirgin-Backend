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

	public function name(string $name, string $column = 'name'): self{
		$this->query->where($column, $name);
		return $this;
	}

	public function category(int $categoryId): self{
		$this->query->where('categoryId', $categoryId);
		return $this;
	}

	public function seller(int $sellerId): self{
		$this->query->where('createdBy', $sellerId);
		return $this;
	}
}