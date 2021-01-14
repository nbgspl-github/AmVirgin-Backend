<?php

namespace App\Resources\Shop\Customer\Catalog\Filters;

use App\Library\Utils\Extensions\Arrays;
use Illuminate\Support\Collection;

class DiscountResource extends AbstractBuiltInResource
{
	const COLUMN = 'discount';
	const KEY = 'filter_discount';
	const TYPE = 'discount';
	const MODE = 'single';
	const LABEL = 'Discount';

	public function withValues (Collection $values) : self
	{
		$this->values = $this->discountDivisions($values);
		return $this;
	}

	private function discountDivisions (Collection $values) : array
	{
		$discountCollection = $values;
		$maxDiscount = $values->max();
		$divisions = Arrays::Empty;
		for ($tenths = 10; $tenths <= 90; $tenths += 10) {
			$itemsInRange = $discountCollection->whereBetween(null, [$tenths, $maxDiscount])->count();
			if ($itemsInRange > 0) {
				Arrays::push($divisions, [
					'limit' => $tenths,
					'count' => $itemsInRange,
				]);
			}
		}
		return $divisions;
	}
}