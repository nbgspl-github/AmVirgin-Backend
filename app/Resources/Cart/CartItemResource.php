<?php

namespace App\Resources\Cart;

use App\Library\Utils\Uploads;
use App\Resources\Products\Customer\OptionResource;
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
			'image' => Uploads::existsUrl($this->primaryImage()),
			'options' => OptionResource::collection($this->options),
		];
	}
}