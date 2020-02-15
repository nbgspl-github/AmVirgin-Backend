<?php

namespace App\Resources\Products\Seller;

use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductEditResource extends JsonResource{
	public static function withoutWrapping(){
		return true;
	}

	public function toArray($request){
		$category = Category::retrieve($this->categoryId);
		if (null($category)) {
			$category = [];
		}
		else {
			$category = new CategoryResource($category);
		}
		return [
			'id' => $this->id,
			'name' => $this->name,
			'category' => $category,
			'productType' => $this->productType,
			'productMode' => $this->productMode,
			'listingType' => $this->listingType,
			'originalPrice' => $this->originalPrice,
			'offerValue' => $this->offerValue,
			'offerType' => $this->offerType,
			'currency' => $this->currency,
			'taxRate' => $this->taxRate,
			'countryId' => $this->countryId,
			'stateId' => $this->stateId,
			'cityId' => $this->cityId,
			'zipCode' => $this->zipCode,
			'address' => $this->zipCode,
			'rating' => $this->rating,
			'shippingCostType' => $this->shippingCostType,
			'shippingCost' => $this->shippingCost,
			'shortDescription' => $this->shortDescription,
			'longDescription' => $this->longDescription,
			'images' => ProductImageEditResource::collection($this->images()->get()),
		];
	}
}