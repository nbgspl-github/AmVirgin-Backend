<?php

namespace App\Resources\Shop\Customer\Catalog\Filters;

use App\Classes\Arrays;
use Illuminate\Support\Collection;

class DiscountResource extends AbstractBuiltInResource{
	public const RequiredColumn = 'discount';

	public function toArray($request){
		return [
			'label' => $this->label(),
			'builtIn' => $this->builtIn(),
			'type' => $this->builtInType(),
			'mode' => $this->mode(),
			'options' => $this->values,
		];
	}

	public function withValues(Collection $values): self{
		$this->values = $this->discountDivisions($values);
		return $this;
	}

	private function discountDivisions(Collection $values): array{
		$discountCollection = $values;
		$divisions = Arrays::Empty;
		for ($tenths = 10; $tenths <= 90; $tenths++) {
			$itemsInRange = $discountCollection->where(static::RequiredColumn, '>=', $tenths)->count();
			if ($itemsInRange > 0) {
				Arrays::push($divisions, $tenths);
			}
		}
		return $divisions;
	}
}