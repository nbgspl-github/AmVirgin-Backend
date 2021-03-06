<?php

namespace App\Resources\Products\Seller;

use App\Library\Utils\Extensions\Time;
use App\Library\Utils\Uploads;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class CatalogListResource extends JsonResource
{
    public function toArray ($request): array
    {
        return [
            'key' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'category' => $this->category->name,
            'sku' => $this->sku,
            'stock' => $this->stock,
            'listingStatus' => $this->listingStatus,
            'listingDate' => Time::mysqlStamp(strtotime($this->created_at)),
            'type' => $this->type,
            'idealFor' => $this->idealFor,
            'originalPrice' => $this->originalPrice,
            'sellingPrice' => $this->sellingPrice,
            'image' => $this->primaryImage,
            'options' => CatalogListOptionResource::collection($this->options()->where('showInCatalogListing', true)->get()),
            'variants' => $this->when($this->type == Product::Type['Variant'], VariantItemResource::collection($this->variants)),
        ];
    }
}