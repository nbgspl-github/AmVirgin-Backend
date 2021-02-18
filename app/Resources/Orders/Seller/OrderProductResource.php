<?php

namespace App\Resources\Orders\Seller;

use App\Library\Utils\Uploads;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource{
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
			'image' => Uploads::existsUrl($this->primaryImage()),
		];
	}
}