<?php

namespace App\Resources\Products\Customer;

use App\Constants\OfferTypes;
use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource {
	public static function withoutWrapping() {
		return true;
	}

	public function toArray($request) {
		$category = Category::retrieve($this->categoryId);

		/**
		 * Calculating applicable discount details.
		 */
		$discount = [
			'type' => OfferTypes::name($this->offerType),
			'value' => $this->offerValue,
			'applicable' => $this->offerValue > 0,
		];
		if (null($category)) {
			$category = [];
		}
		else {
			$category = new CategoryResource($category);
		}
		return [
			'name' => $this->name,
			'category' => $category,
			'rating' => $this->rating,
			'price' => $this->originalPrice,
			'discount' => $discount,
			'shortDescription' => $this->shortDescription,
			'images' => ProductImageResource::collection($this->images),
		];
	}
}