<?php

namespace App\Resources\Products\Customer;

use App\Models\Product;
use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class CartProductResource extends JsonResource{
	public function toArray($request){
		return [
			'key' => $this->id(),
			'slug' => $this->slug(),
			'brand' => $this->brand->name,
			'name' => $this->name(),
			'price' => [
				'original' => $this->originalPrice(),
				'selling' => $this->sellingPrice(),
			],
			'rating' => $this->rating(),
			'image' => SecuredDisk::existsUrl($this->primaryImage()),
		];
	}
}