<?php

namespace App\Resources\Shop\Customer\HomePage;

use App\Resources\Products\Customer\ImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EntertainmentProductResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'price' => $this->originalPrice,
			'images' => ImageResource::collection($this->images),
		];
	}
}