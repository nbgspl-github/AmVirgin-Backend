<?php

namespace App\Queries;

use App\Classes\Arrays;
use App\Classes\Time;
use App\Filters\BrandFilter;
use App\Filters\CategoryFilter;
use App\Filters\DiscountFilter;
use App\Filters\GenderFilter;
use App\Filters\PriceRangeFilter;
use App\Models\CatalogFilter;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductQuery extends AbstractQuery{
	use PriceRangeFilter, BrandFilter, GenderFilter, CategoryFilter, DiscountFilter;

	protected function __construct(){
		parent::__construct();

		// To make sure we don't mistakenly apply wrong filter to wrong
		// category we first get the list of available filters, and check if the
		// incoming filter exists in that list, only then we can apply if.

		// Call required filters specified in request.
		if (request()->has('filters') && request()->has('categoryId')) {
			$availableCatalogFilters = CatalogFilter::startQuery()->category(request('categoryId'))->builtIn()->get();
		}
	}

	public static function begin(): self{
		return new self();
	}

	public function displayable(): self{
//		$this->query->where('draft', false)->where('approved', true)->whereNotNull('approvedBy');
		return $this;
	}

	public function singleVariantMode(): self{
		$this->query->groupBy('group');
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

	protected function model(): string{
		return Product::class;
	}
}