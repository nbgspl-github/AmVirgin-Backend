<?php

namespace App\Resources\Cart;

use App\Library\Utils\Uploads;
use App\Resources\Products\Customer\OptionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray ($request): array
    {
        return [
            'name' => $this->name,
            'price' => [
                'original' => $this->originalPrice,
                'selling' => $this->sellingPrice,
            ],
            'maxAllowedQuantity' => $this->maxQuantityPerOrder,
            'image' => $this->primaryImage,
            'options' => OptionResource::collection($this->options),
        ];
    }
}