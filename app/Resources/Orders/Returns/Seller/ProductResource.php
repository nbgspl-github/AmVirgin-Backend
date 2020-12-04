<?php

namespace App\Resources\Orders\Returns\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
	public function toArray ($request)
	{
		return [
			'key' => $this->id,
			'name' => $this->name,
			'description' => $this->description,
			'idealFor' => $this->idealFor,
			'sku' => $this->sku,
			'styleCode' => $this->styleCode,
			'procurementSla' => $this->procurementSla,
		];
	}
}