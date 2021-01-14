<?php

namespace App\Resources\Shop\Customer\Catalog\Filters;

use App\Library\Utils\Extensions\Arrays;
use Illuminate\Support\Collection;

class PriceRangeResource extends AbstractBuiltInResource
{
	const COLUMN = 'originalPrice';
	const KEY = 'filter_price';
	const TYPE = 'price';
	const MODE = 'single';
	const LABEL = 'Price';

	public function withValues (Collection $values) : self
	{
		// We get a Collection of values for the column we want to access.
		// What we need to do is, if this specific filter lists out its operable set of values,
		// we transform those values as need, otherwise we leave the options as blank array.
		$this->values = $this->priceDivisions($values);
		return $this;
	}

	private function priceDivisions (Collection $values) : array
	{
		$priceCollection = $values;
		$minimumPrice = $priceCollection->min();
		$maximumPrice = $priceCollection->max();
		$itemCount = $priceCollection->count();
		if ($itemCount == 0) {
			// If there are no products, just create a 0 - 0 range filter.
			return [
				'upper' => 0,
				'lower' => 0,
				'count' => 0,
			];
		}
		$boundaries = config('filters.price.boundaries');
		$divisions = -1;

		// Find the highest boundary value by comparing maxPrice.
		foreach ($boundaries as $key => $value) {
			if ($key >= $maximumPrice) {
				$divisions = $value;
				break;
			}
		}

		// If the threshold value wasn't matched, means the maximum price has
		// exceeded the defined threshold limit. Hence we revert to default divisions.
		if ($divisions == -1) {
			$divisions = config('filters.price.static.divisions');
		}

		// Now we can calculate a median value, upon which we'll create price segments.
		// We must also ensure to divide even by even only. If that's not the case, we'll add 1 to all ranges.
		$diff = $maximumPrice - $minimumPrice;
		self::even($diff) && self::even($divisions) ? $neutralizer = 0 : $neutralizer = 1;
		$median = (int)($diff / $divisions);

		$sections = Arrays::Empty;
		for ($i = 0; $i < $divisions; $i++) {
			$lastMinimum = $minimumPrice;
			$minimumPrice = $minimumPrice + $median + $neutralizer;
			$productCount = $priceCollection->whereBetween(null, [$lastMinimum, $minimumPrice])->count();
			if ($productCount > 0) {
				Arrays::push($sections, [
					'upper' => $lastMinimum,
					'lower' => $minimumPrice,
					'count' => $productCount,
				]);
			}
		}
		return $sections;
	}

	protected static function even (int $number) : bool
	{
		return $number % 2 == 0;
	}
}