<?php

namespace App\Queries;

use App\Filters\BrandFilter;
use App\Filters\CategoryFilter;
use App\Filters\DiscountFilter;
use App\Filters\GenderFilter;
use App\Filters\PriceRangeFilter;
use App\Library\Utils\Extensions\Time;
use App\Models\CatalogFilter;
use App\Models\Category;
use App\Models\Product;
use App\Queries\Traits\SellerAuthentication;

class ProductQuery extends AbstractQuery
{
	use PriceRangeFilter, BrandFilter, GenderFilter, CategoryFilter, DiscountFilter;
	use SellerAuthentication;

	protected function __construct ()
	{
		parent::__construct();

		// To make sure we don't mistakenly apply wrong filter to wrong
		// category we first get the list of available filters, and check if the
		// incoming filter exists in that list, only then we can apply if.

		// Call required filters specified in request.
		if (request()->has('filters') && request()->has('categoryId')) {
			$availableCatalogFilters = CatalogFilter::startQuery()->category(request('categoryId'))->builtIn()->get();
		}
	}

	public static function begin (): self
	{
		return new self();
	}

	public function displayable (): self
	{
		$this->query->where('draft', false)->where('listingStatus', Product::ListingStatus['Active'])->where('approved', true);
		return $this;
	}

	public function singleVariantMode (): self
	{
		$this->query->groupBy('group');
		return $this;
	}

	public function simple (bool $invert = false): self
	{
		if (!$invert)
			$this->query->where('type', Product::Type['Simple']);
		else
			$this->query->where('type', '!=', Product::Type['Simple']);
		return $this;
	}

	public function variant (bool $invert = false): self
	{
		if (!$invert)
			$this->query->where('type', Product::Type['Simple']);
		else
			$this->query->where('type', '!=', Product::Type['Simple']);
		return $this;
	}

	public function group (string $groupGuid): self
	{
		$this->query->where('group', $groupGuid);
		return $this;
	}

	public function categoryOrDescendant (int $categoryId)
	{
		$category = Category::find($categoryId);
		$descendants = $category->descendants(true)->pluck('id')->toArray();
		$this->query->whereIn('categoryId', $descendants);
		return $this;
	}

	public function promoted (): self
	{
		$this->query->where([
			['promoted', true],
			['promotionStart', '<=', Time::mysqlStamp()],
			['promotionEnd', '>', Time::mysqlStamp()],
		]);
		return $this;
	}

	public function notPromoted (): self
	{
		$this->query->where('promoted', false);
		return $this;
	}

	public function hotDeal (): self
	{
		$this->query->where('specials->hotDeal', true);
		return $this;
	}

	public function seller (int $sellerId)
	{
		$this->query->where('sellerId', $sellerId);
		return $this;
	}

	protected function model (): string
	{
		return Product::class;
	}

	public function search (string $keywords, string $column = 'name'): self
	{
		$this->query->where($column, 'LIKE', "%{$keywords}%");
		return $this;
	}

	public function orSearch (string $keywords, string $column = 'name'): self
	{
		$this->query->orWhere($column, 'LIKE', "%{$keywords}%");
		return $this;
	}

	public function withWhere (string $column = '', string $keywords = ''): self
	{
		$this->query->where($column, $keywords);
		return $this;
	}
}