<?php

namespace App\Resources\Products\Seller;

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
			'id'               => $this->id,
			'name'             => $this->name,
			'category'         => $category,
			'productType'      => $this->productType,
			'productMode'      => $this->productMode,
			'listingType'      => $this->listingType,
			'originalPrice'    => $this->originalPrice,
			'offerValue'       => $this->offerValue,
			'offerType'        => $this->offerType,
			'currency'         => $this->currency,
			'taxRate'          => $this->taxRate,
			'countryId'        => $this->countryId,
			'stateId'          => $this->stateId,
			'cityId'           => $this->cityId,
			'zipCode'          => $this->zipCode,
			'address'          => $this->zipCode,
			'rating'           => $this->rating,
			'shippingCostType' => $this->shippingCostType,
			'shippingCost'     => $this->shippingCost,
			'shortDescription' => $this->shortDescription,
			'longDescription'  => $this->longDescription,
			'images'           => ProductImageResource::collection($this->images()->get()),
			'sellingPrice'     => $this->sellingPrice,
			'sku'              => $this->sku,
			'stock'            => $this->stock,
			'hsn'              => $this->hsn,
			'taxCode'          => $this->taxCode,
			'fulfilmentBy'     => $this->fulfilmentBy,
			'procurementSla'   => $this->procurementSla,
			'localShippingCost'=> $this->localShippingCost,
			'zonalShippingCost'=> $this->zonalShippingCost,
			'internationalShippingCost' => $this->internationalShippingCost,
			'packageWeight'    => $this->packageWeight,
			'packageLength'    => $this->packageLength,
			'packageHeight'    => $this->packageHeight,
			'idealFor'         => $this->idealFor,
			'videoUrl'         => $this->videoUrl,
			'domesticWarranty' => $this->domesticWarranty,
			'internationalWarranty' => $this->internationalWarranty,
			'warrantySummary' => $this->warrantySummary,
			'warrantyServiceType' => $this->warrantyServiceType,
			'coveredInWarranty' => $this->coveredInWarranty,
			'notCoveredInWarranty' => $this->notCoveredInWarranty,
		];
	}
}