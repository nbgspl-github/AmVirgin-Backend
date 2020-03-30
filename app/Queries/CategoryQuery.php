<?php

namespace App\Queries;

use App\Models\Category;

class CategoryQuery extends AbstractQuery{
	public static function begin(): CategoryQuery{
		return new self();
	}

	protected function model(): string{
		return Category::class;
	}

	public function brandInFocus($invert = false){
		$this->query->where('specials->brandInFocus', !$invert);
		return $this;
	}

	public function popularCategory($invert = false){
		$this->query->where('specials->popularCategory', !$invert);
		return $this;
	}

	public function trendingNow($invert = false){
		$this->query->where('specials->trendingNow', !$invert);
		return $this;
	}

	public function isRoot(bool $yes = true){
		if (!$yes)
			$this->query->where('type', '!=', Category::Types['Root']);
		else
			$this->query->where('type', Category::Types['Root']);
		return $this;
	}

	public function isCategory(){
		$this->query->where('type', Category::Types['Category']);
		return $this;
	}

	public function isSubCategory(){
		$this->query->where('type', Category::Types['SubCategory']);
		return $this;
	}

	public function isVertical(){
		$this->query->where('type', Category::Types['Vertical']);
		return $this;
	}
}