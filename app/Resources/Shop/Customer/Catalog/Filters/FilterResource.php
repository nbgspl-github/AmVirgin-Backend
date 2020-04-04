<?php

namespace App\Resources\Shop\Customer\Catalog\Filters;

use Illuminate\Http\Resources\Json\JsonResource;

class FilterResource extends JsonResource{
	public function toArray($request){
		return [
			'key' => $this->id(),
			'label' => $this->label(),
			'builtIn' => $this->builtIn(),
			'allowMultiValue' => $this->allowMultiValue(),
			'options' => $this->attribute->values(),
		];
	}
}