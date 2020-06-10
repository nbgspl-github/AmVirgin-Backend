<?php

namespace App\Resources\Shop\Customer\Catalog\Filters;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class FilterResource extends AbstractBuiltInResource{
	public function toArray($request){
		return [
			'key' => $this->id(),
			'label' => $this->label(),
			'type' => 'custom',
			'mode' => $this->mode(),
			'options' => $this->attribute->values(),
		];
	}

	public function withValues(Collection $values): AbstractBuiltInResource{
		$this->values = $values->toArray();
		return $this;
	}
}