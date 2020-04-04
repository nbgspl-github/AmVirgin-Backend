<?php

namespace App\Queries;

use App\Classes\Arrays;
use App\Classes\Time;
use App\Filters\BrandFilter;
use App\Filters\GenderFilter;
use App\Filters\PriceRangeFilter;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductQuery extends AbstractQuery{
	use PriceRangeFilter, BrandFilter, GenderFilter;

	protected const PriceColumnKey = 'originalPrice';
	protected const BrandColumnKey = 'brandId';
	protected const GenderColumnKey = 'idealFor';

	protected function __construct(){
		parent::__construct();

		// Call required filters specified in request.
		if (request()->has(''))
	}

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