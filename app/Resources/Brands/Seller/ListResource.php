<?php

namespace App\Resources\Brands\Seller;

use App\Library\Utils\Extensions\Str;
use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'requestId' => $this->requestId(),
            'requestTime' => $this->created_at,
            'key' => $this->id(),
            'name' => $this->name(),
            'logo' => SecuredDisk::existsUrl($this->logo()),
            'status' => $this->status(),
            'verticalName' => $this->category()->exists() ? $this->category->name : Str::Empty
        ];
    }
}