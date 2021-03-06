<?php

namespace App\Resources\Products\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CatalogListOptionResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'label' => $this->label,
			'value' => $this->value,
		];
	}
}