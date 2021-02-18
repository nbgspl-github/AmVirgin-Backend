<?php

namespace App\Resources\Products\Customer;

use App\Library\Utils\Uploads;
use Illuminate\Http\Resources\Json\JsonResource;

class CatalogListResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'slug' => $this->slug,
			'brand' => $this->brand->name ?? null,
			'name' => $this->name,
			'price' => [
				'original' => $this->originalPrice,
				'selling' => $this->sellingPrice,
			],
			'rating' => $this->rating,
			'image' => Uploads::existsUrl($this->primaryImage),
			'gallery' => ImageResource::collection($this->images),
			'options' => CatalogListOptionResource::collection($this->options()->where('showInCatalogListing', true)->get()),
		];
	}
}