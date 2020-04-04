<?php

namespace App\Queries;

use App\Models\CatalogFilter;

class CatalogFilterQuery extends AbstractQuery{

	protected function model(): string{
		return CatalogFilter::class;
	}

	public static function begin(): self{
		return new self();
	}

	public function category(int $categoryId): self{
		$this->query->where('categoryId', $categoryId);
		return $this;
	}

	public function builtIn(bool $yes = true){
		$this->query->where('builtIn', $yes);
		return $this;
	}

	public function displayable(): AbstractQuery{
		return $this;
	}
}