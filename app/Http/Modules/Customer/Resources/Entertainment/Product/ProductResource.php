<?php

namespace App\Http\Modules\Customer\Resources\Entertainment\Product;

class ProductResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'price' => $this->originalPrice,
			'images' => \App\Http\Modules\Customer\Resources\Entertainment\Product\ImageResource::collection($this->images),
		];
	}
}