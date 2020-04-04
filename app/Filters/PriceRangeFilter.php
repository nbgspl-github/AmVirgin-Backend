<?php

namespace App\Filters;

use App\Classes\Arrays;
use Illuminate\Support\Collection;

trait PriceRangeFilter{
	public function priceRange(array ...$ranges): self{
		$column = defined(static::PriceColumnKey) ? static::PriceColumnKey : 'price';
		foreach ($ranges as $range)
			$this->query->whereBetween($column, $range[0], $range[1]);
		return $this;
	}

	public function priceDivisions(): array{
		$priceCollection = $this->query->orderBy(static::PriceColumnKey)->get(static::PriceColumnKey);
		$minimumPrice = $priceCollection->min();
		$maximumPrice = $priceCollection->max();
		$itemCount = $priceCollection->count();
		$thresholds = config('filters.price.threshold');
		$divisions = -1;
		// Find the highest threshold value by comparing maxPrice.
		foreach ($thresholds as $threshold) {
			if ($threshold >= $maximumPrice) {
				$divisions = $thresholds[$threshold];
				break;
			}
		}

		// If the threshold value wasn't matched, means the maximum price has
		// exceed defined threshold limit. Hence we revert to default divisions.
		if ($divisions == -1) {
			$divisions = config('filters.price.static.divisions');
		}

		// Now we can calculate a median value, upon which we'll create price segments.
		// We must also ensure to divide even by even only. If that's not the case, we'll add 1 to all ranges.
		$rangeFixation = 0;
		$diff = $maximumPrice - $minimumPrice;
		self::even($diff) && self::even($divisions) ? $rangeFixation = 0 : $rangeFixation = 1;
		$median = $diff / $divisions;

		$ranges = Arrays::Empty;
		for ($i = 0; $i < $divisions; $i++) {
			$ranges[] = [
				'start' => $minimumPrice,
				'end' => $minimumPrice + $median + $rangeFixation,
			];
			$minimumPrice = $minimumPrice + $median + $rangeFixation;
		}
		return $ranges;
	}

	protected static function even(int $number){
		return $number % 2 == 0;
	}

	protected function calculateRanges(array $prices){

		max
	}
}