<?php

namespace App\Resources\Products\Customer;

use App\Classes\Arrays;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductWithVariantsResource extends JsonResource{
	public function toArray($request){
		return [
			'name' => $this->name(),
			'category' => new ProductCategoryResource($this->category),
			'seller' => new ProductSellerResource($this->seller),
			'brand' => new ProductBrandResource($this->brand),
			'price' => [
				'original' => $this->originalPrice(),
				'selling' => $this->sellingPrice(),
				'discount' => [
					'has' => $this->hasDiscount(),
					'value' => $this->calculateDiscount(),
				],
			],
			'fulfillmentBy' => $this->fulfilledBy(),
			'currency' => $this->currency(),
			'',
		];
	}

	public function calculateDiscount(): int{
		$actual = $this->originalPrice();
		$current = $this->sellingPrice();
		$difference = $actual - $current;
		if (!$this->hasDiscount())
			return 0;
		else {
			return intval(($difference * 100.0) / $actual);
		}
	}

	public function hasDiscount(): bool{
		return $this->sellingPrice() < $this->originalPrice();
	}

	public function fulfilledBy(){
		return Arrays::search($this->fulfillmentBy(), Product::FulfillmentBy);
	}
}