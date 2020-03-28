<?php

namespace App\Queries;

use App\Classes\Time;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductQuery extends BaseQuery{
	public static function begin(){
		return new self();
	}

	public function displayable(): self{
		$this->query->where('draft', false);
		return $this;
	}

	public function promoted(): self{
		$this->query->where([
			['promoted', true],
			['promotionStart', '<=', Time::mysqlStamp()],
			['promotionEnd', '>', Time::mysqlStamp()],
		]);
		return $this;
	}

	public function notPromoted(): self{
		$this->query->where('promoted', false);
		return $this;
	}

	public function hotDeal(){
		$this->query->where('specials->hotDeal', true);
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

	protected function model(): string{
		return Product::class;
	}
}