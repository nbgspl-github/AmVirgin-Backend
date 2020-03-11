<?php

namespace App\Resources\Products\Customer;

use App\Constants\OfferTypes;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\ProductAttribute;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource {
	public static function withoutWrapping() {
		return true;
	}

	public function toArray($request) {
		$category = Category::retrieve($this->categoryId);
		$attributes = $this->attributes;
		$distinctIds = $attributes->unique('attributeId')->values();
		$distinctIds->transform(function ($id) {
			$values = ProductAttribute::where('productId', $this->id)->where('attributeId', $id->attributeId)->get()->transform(function (ProductAttribute $attribute) {
				$value = AttributeValue::find($attribute->valueId);
				return [
					'key' => $value->id,
					'value' => $value->value,
				];
			});
			$attribute = Attribute::retrieve($id->attributeId);
			return [
				'key' => $attribute->id,
				'name' => $attribute->name,
				'values' => $values,
			];
		});
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
			'key' => $this->id,
			'name' => $this->name,
			'category' => $category,
			'rating' => $this->rating,
			'price' => $this->originalPrice,
			'discount' => $discount,
			'shortDescription' => $this->shortDescription,
			'images' => ProductImageResource::collection($this->images),
			'attributes' => $distinctIds,
		];
	}
}