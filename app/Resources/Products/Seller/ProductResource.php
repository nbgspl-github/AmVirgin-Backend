<?php

namespace App\Resources\Products\Seller;

use App\Classes\Arrays;
use App\Classes\Str;
use App\Models\Category;
use App\Models\Product;
use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends AbstractProductResource{
	protected ?CategoryResource $categoryResource;
	protected ?BrandResource $brandResource;
	protected array $warranty;

	public function __construct($resource){
		parent::__construct($resource);
		$this->categoryResource = new CategoryResource($this->category);
		$this->brandResource = new BrandResource($this->brand);
		$this->warranty = [
			'domestic' => $this->domesticWarranty(),
			'international' => $this->internationalWarranty(),
			'unit' => 'Days',
			'summary' => $this->warrantySummary(),
			'serviceType' => $this->serviceType(),
			'covered' => $this->coveredInWarranty(),
			'excluded' => $this->notCoveredInWarranty(),
		];
	}

	public function toArray($request){
		return [
			'key' => $this->id(),
			'name' => $this->name(),
			'description' => $this->description(),
			'category' => $this->categoryResource,
			'brand' => $this->brandResource,
			'listingStatus' => $this->listingStatus(),
			'type' => $this->type(),
			'idealFor' => $this->idealFor(),
			'originalPrice' => $this->originalPrice(),
			'sellingPrice' => $this->sellingPrice(),
			'fulfillmentBy' => $this->fulfillmentBy(),
			'hsn' => $this->hsn(),
			'currency' => $this->currency(),
			'stock' => $this->stock(),
			'lowStockThreshold' => $this->lowStockThreshold(),
			'sku' => $this->sku(),
			'styleCode' => $this->styleCode(),
			'trailer' => SecuredDisk::existsUrl($this->trailer()),
			'procurementSla' => $this->procurementSla(),
			'localShippingCost' => $this->localShippingCost(),
			'zonalShippingCost' => $this->zonalShippingCost(),
			'internationalShippingCost' => $this->internationalShippingCost(),
			'packageWeight' => $this->packageWeight(),
			'packageLength' => $this->packageLength(),
			'packageBreadth' => $this->packageBreadth(),
			'packageHeight' => $this->packageHeight(),
			'domesticWarranty' => $this->domesticWarranty(),
			'internationalWarranty' => $this->internationalWarranty(),
			'warrantySummary' => $this->warrantySummary(),
			'warrantyServiceType' => $this->serviceType(),
			'coveredInWarranty' => $this->coveredInWarranty(),
			'notCoveredInWarranty' => $this->notCoveredInWarranty(),
			'maxQuantityPerOrder' => $this->maxQuantityPerOrder(),
			'attributes' => AttributeResource::collection($this->attributes),
			'files' => ImageResource::collection($this->images),
			'variants' => $this->when($this->type() == Product::Type['Variant'], VariantItemResource::collection($this->variants)),
		];
	}

	public function serviceType(){
		return Arrays::search($this->warrantyServiceType(), Product::WarrantyServiceType);
	}

	public function variants(){
		$variants = $this->variants;
	}
}