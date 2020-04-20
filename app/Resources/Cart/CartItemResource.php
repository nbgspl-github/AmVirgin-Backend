<?php

namespace App\Resources\Cart;

use App\Resources\Products\Customer\OptionResource;
use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource{
	public static function withoutWrapping(){
		return true;
	}

	public function toArray($request){
		return [
			'name' => $this->name(),
			'price' => [
				'original' => $this->originalPrice(),
				'selling' => $this->sellingPrice(),
			],
			'maxAllowedQuantity' => $this->maxQuantityPerOrder(),
			'image' => SecuredDisk::existsUrl($this->primaryImage()),
			'options' => OptionResource::collection($this->options),
		];
	}
}