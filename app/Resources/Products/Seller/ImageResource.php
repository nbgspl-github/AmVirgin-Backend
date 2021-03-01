<?php

namespace App\Resources\Products\Seller;

use App\Library\Utils\Uploads;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    public function toArray ($request): array
    {
        return [
            'key' => $this->id,
            'url' => $this->path,
        ];
    }
}