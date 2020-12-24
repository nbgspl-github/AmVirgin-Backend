<?php

namespace App\Queries;

use App\Models\NewsItem;

class NewsItemQuery extends AbstractQuery
{

	protected function model () : string
	{
		return NewsItem::class;
	}

	public static function begin () : NewsItemQuery
	{
		return new self();
	}

	public function displayable () : NewsItemQuery
	{
		return $this;
	}

	public function trending () : self
	{
		$this->query->where('trending', true);
		return $this;
	}

	public function orderByTrending () : self
	{
		$this->query->orderBy('trending_rank');
		return $this;
	}
}