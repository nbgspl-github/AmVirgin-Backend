<?php

namespace App\Resources\Products\Seller;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\ProductAttribute;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductEditResource extends JsonResource {
	public static function withoutWrapping() {
		return true;
	}

	public function toArray($request) {
		$category = Category::find($this->categoryId);
		$attributes = $this->attributes;
		$distinctIds = $attributes->unique('attributeId')->values();
		$distinctIds->transform(function ($id) {
			$values = ProductAttribute::where('productId', $this->id)->where('attributeId', $id->attributeId)->get()->transform(function (ProductAttribute $attribute) {
				$value = AttributeValue::find($attribute->valueId);
				return [
					'key' => $value->id,
					'uniqueId' => $attribute->getKey(),
					'value' => $value->value,
				];
			});
			$attribute = Attribute::find($id->attributeId);
			return [
				'key' => $attribute->id,
				'name' => $attribute->name,
				'values' => $values,
			];
		});
		if (is_null($category)) {
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
			'visibility' => $this->visibility,
			'promotionStart' => $this->promotionStart,
			'promotionEnd' => $this->promotionEnd,
			'sku' => $this->sku,
			'stock' => $this->stock,
			'draft' => $this->draft,
			'shippingCostType' => $this->shippingCostType,
			'shippingCost' => $this->shippingCost,
			'shortDescription' => $this->shortDescription,
			'longDescription' => $this->longDescription,
			'images' => ProductImageEditResource::collection($this->images),
			'attributes' => $distinctIds,
		];
	}
}