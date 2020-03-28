<?php

namespace App\Queries;

use App\Models\Category;

class CategoryQuery extends AbstractQuery{
	public static function begin(): AbstractQuery{
		return new self();
	}

	protected function model(): string{
		return Category::class;
	}

	public function brandInFocus(){
		$this->query->where('specials->brandInFocus', true);
		return $this;
	}

	public function popularCategory(){
		$this->query->where('specials->popularCategory', true);
		return $this;
	}

	public function trendingNow(){
		$this->query->where('specials->trendingNow', true);
		return $this;
	}
}