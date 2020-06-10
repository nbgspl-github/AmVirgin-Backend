<?php

namespace App\Resources\Shop\Customer\Catalog\Filters;

use Illuminate\Support\Collection;

class BrandResource extends AbstractBuiltInResource{
	public const RequiredColumn = 'brandId';

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
		return $this;
	}
}