<?php

namespace App\Queries;

use App\Models\Category;

class CategoryQuery extends AbstractQuery
{
	public static function begin () : CategoryQuery
	{
		return new self();
	}

	protected function model () : string
	{
		return Category::class;
	}

	public function brandInFocus ($invert = false) : CategoryQuery
	{
		$this->query->where('specials->brandInFocus', !$invert);
		return $this;
	}

	public function popularCategory ($invert = false) : CategoryQuery
	{
		$this->query->where('specials->popularCategory', !$invert);
		return $this;
	}

	public function trendingNow ($invert = false) : CategoryQuery
	{
		$this->query->where('specials->trendingNow', !$invert);
		return $this;
	}

	public function isRoot (bool $yes = true) : CategoryQuery
	{
		if (!$yes)
			$this->query->where('type', '!=', \App\Library\Enums\Categories\Types::Root);
		else
			$this->query->where('type', \App\Library\Enums\Categories\Types::Root);
		return $this;
	}

	public function isCategory () : CategoryQuery
	{
		$this->query->where('type', \App\Library\Enums\Categories\Types::Category);
		return $this;
	}

	public function isSubCategory () : CategoryQuery
	{
		$this->query->where('type', \App\Library\Enums\Categories\Types::SubCategory);
		return $this;
	}

	public function isVertical () : CategoryQuery
	{
		$this->query->where('type', \App\Library\Enums\Categories\Types::Vertical);
		return $this;
	}

	public function displayable () : AbstractQuery
	{
		return $this;
	}
}