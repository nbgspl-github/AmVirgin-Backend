<?php

namespace App\Resources\Products\Customer;

use App\Classes\Arrays;
use App\Classes\Str;
use App\Models\Category;
use App\Models\Product;
use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class VariantProductResource extends AbstractProductResource{
	protected CategoryResource $categoryResource;
	protected SellerResource $sellerResource;
	protected BrandResource $brandResource;
	protected array $warranty;

	public function __construct($resource){
		parent::__construct($resource);
		$this->categoryResource = new CategoryResource($this->categoryResource);
		$this->sellerResource = new SellerResource($this->sellerResource);
		$this->brandResource = new BrandResource($this->brandResource);
		$this->warranty = [
			'domestic' => $this->domesticWarranty(),
			'international' => $this->internationalWarranty(),
			'unit' => 'days',
			'summary' => $this->warrantySummary(),
			'serviceType' => $this->serviceType(),
			'covered' => $this->coveredInWarranty(),
			'excluded' => $this->notCoveredInWarranty(),
		];
	}

	public function toArray($request){
		return [
			'name' => $this->name(),
			'description' => $this->description(),
			'category' => $this->categoryResource,
			'seller' => $this->sellerResource,
			'brand' => $this->brandResource,
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
			'rating' => $this->rating(),
			'stock' => [
				'isLowInStock' => $this->isLowInStock(),
				'available' => $this->inStock(),
			],
			'warranty' => $this->warranty,
			'maxQuantity' => $this->maxQuantityPerOrder(),
			'media' => [
				'trailer' => [
					'has' => SecuredDisk::access()->exists($this->trailer()),
					'link' => SecuredDisk::existsUrl($this->trailer()),
				],
				'gallery' => ImageResource::collection($this->images),
			],
			'variants' => [],
		];
	}

	public function serviceType(){
		return Arrays::search($this->warrantyServiceType(), Product::WarrantyServiceType);
	}

	public function variants(){
		$variants = $this->variants;
	}
}