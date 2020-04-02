<?php

namespace App\Queries;

use App\Classes\Arrays;
use App\Classes\Time;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductQuery extends AbstractQuery{
	public static function begin(): self{
		return new self();
	}

	public function displayable(): self{
		$this->query->where('draft', false)->where('approved', true)->whereNotNull('approvedBy')->whereNull('parentId');
		return $this;
	}

	public function categoryOrDescendant(int $categoryId){
		$descendants = Arrays::Empty;
		$category = Category::retrieve($categoryId);
		$category->c
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

	protected function model(): string{
		return Product::class;
	}
}