<?php

namespace App\Resources\Products\Seller;

use App\Models\Product;
use App\Resources\Products\Seller\CatalogListOptionResource;
use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class CatalogListResource extends JsonResource{
	public function toArray($request){
		return [
			'key' => $this->id(),
			'name' => $this->name(),
			'slug' => $this->slug(),
			'category' => $this->category->name(),
			'sku' => $this->sku(),
			'stock' => $this->stock(),
			'listingStatus' => $this->listingStatus(),
			'type' => $this->type(),
			'idealFor' => $this->idealFor(),
			'originalPrice' => $this->originalPrice(),
			'sellingPrice' => $this->sellingPrice(),
			'image' => SecuredDisk::existsUrl($this->primaryImage()),
			'options' => CatalogListOptionResource::collection($this->options()->where('showInCatalogListing', true)->get()),
			'variants' => $this->when($this->type() == Product::Type['Variant'], VariantItemResource::collection($this->variants)),
		];
	}
}