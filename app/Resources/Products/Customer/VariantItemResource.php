<?php

namespace App\Resources\Products\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class VariantItemResource extends AbstractProductResource{
	public function toArray($request){
		return [
			'key' => $this->id(),
			'name' => $this->name(),
			'price' => [
				'original' => $this->originalPrice(),
				'selling' => $this->sellingPrice(),
				'discount' => [
					'has' => $this->hasDiscount(),
					'value' => $this->calculateDiscount(),
				],
			],
			'stock' => [
				'isLowInStock' => $this->isLowInStock(),
				'available' => $this->inStock(),
			],
			'options' => [

			],
		];
	}
}