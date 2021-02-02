<?php

namespace App\Resources\Products\Customer;

use App\Library\Utils\Extensions\Arrays;
use App\Models\Product;

class ProductResource extends AbstractProductResource
{
	protected ?CategoryResource $categoryResource;
	protected ?SellerResource $sellerResource;
	protected ?BrandResource $brandResource;
	protected array $warranty;

	public function __construct ($resource)
	{
		parent::__construct($resource);
		$this->categoryResource = new CategoryResource($this->category);
		$this->sellerResource = new SellerResource($this->seller);
		$this->brandResource = new BrandResource($this->brand);
		$this->warranty = [
			'domestic' => $this->domesticWarranty,
			'international' => $this->internationalWarranty,
			'unit' => 'Days',
			'summary' => $this->warrantySummary,
			'serviceType' => $this->serviceType,
			'covered' => $this->coveredInWarranty,
			'excluded' => $this->notCoveredInWarranty,
		];
	}

	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'name' => $this->name,
			'description' => $this->description,
			'type' => $this->type,
			'category' => $this->categoryResource,
			'seller' => $this->sellerResource,
			'brand' => $this->brandResource,
			'price' => [
				'original' => $this->originalPrice,
				'selling' => $this->sellingPrice,
			],
			'fulfillmentBy' => $this->fulfillmentBy,
			'currency' => $this->currency,
			'rating' => $this->rating,
			'stock' => [
				'isLow' => $this->isLowInStock(),
				'remaining' => $this->inStock(),
			],
			'warranty' => $this->warranty,
			'maxQuantity' => $this->maxQuantityPerOrder,
			'media' => [
				'trailer' => $this->trailer,
				'gallery' => ImageResource::collection($this->images),
			],
			'details' => DetailResource::collection($this->specs),
			'options' => OptionResource::collection($this->options),
			'variants' => $this->when($this->type == Product::Type['Variant'], VariantItemResource::collection($this->variants)),
			'similar' => CatalogListResource::collection($this->similar()->get())
		];
	}

	public function serviceType ()
	{
		return Arrays::search($this->warrantyServiceType, Product::WarrantyServiceType);
	}

	public function variants ()
	{
		$variants = $this->variants;
	}
}