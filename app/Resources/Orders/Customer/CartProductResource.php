<?php

namespace App\Resources\Orders\Customer;

use App\Library\Utils\Uploads;
use Illuminate\Http\Resources\Json\JsonResource;

class CartProductResource extends JsonResource
{
    public function toArray ($request): array
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
            'image' => $this->primaryImage,
        ];
    }
}
