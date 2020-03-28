<?php

namespace App\Queries;

use App\Classes\Time;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductQuery{
	protected $model = Product::class;
	private Builder $query;

	public static function new(){
		return new self();
	}

	private function initializeIfNull(){
		if ($this->query == null)
			$this->query = $this->model::query();
	}

	public function displayable(): self{
		$this->initializeIfNull();
		$this->query->where('draft', false);
		return $this;
	}

	public function promoted(): self{
		$this->initializeIfNull();
		$this->query->where([
			['promoted', true],
			['promotionStart', '<=', Time::mysqlStamp()],
			['promotionEnd', '>', Time::mysqlStamp()],
		]);
		return $this;
	}

	public function notPromoted(): self{
		$this->initializeIfNull();
		$this->query->where('promoted', false);
		return $this;
	}

	public function first(){
		return $this->query->first();
	}

	public function firstOrFail(){
		return $this->query->firstOrFail();
	}

	public function get(){
		return $this->query->get();
	}
}