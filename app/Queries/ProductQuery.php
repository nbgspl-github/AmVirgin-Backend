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
		$category = Category::retrieve($categoryId);
		$descendants = $category->descendants(true)->pluck('id')->toArray();
		$this->query->whereIn('categoryId', $descendants);
		return $this;
	}

	public function key(int $productId){
		$this->query->where('id', $productId);
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

	public function hotDeal(): self{
		$this->query->where('specials->hotDeal', true);
		return $this;
	}

	public function priceRange(int $start, int $end): self{

	}

	public function category(...$categories): self{

	}

	public function brand(...$brands): self{

	}

	public function color(...$colors): self{

	}

	public function idealFor(string $gender): self{

	}

	protected function model(): string{
		return Product::class;
	}
}