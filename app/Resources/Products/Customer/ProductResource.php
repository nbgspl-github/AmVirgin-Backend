<?php

namespace App\Resources\Products\Customer;

use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource {
	public static function withoutWrapping() {
		return true;
	}

	public function toArray($request) {
		$category = Category::retrieve($this->categoryId);
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
			'shortDescription' => $this->shortDescription,
			'images' => ProductImageResource::collection($this->images),
		];
	}
}