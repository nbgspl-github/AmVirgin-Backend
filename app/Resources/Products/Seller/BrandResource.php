<?php

namespace App\Resources\Products\Seller;

use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource{
	public function toArray($request){
		return [
			'key' => $this->id(),
			'name' => $this->name(),
			'logo' => SecuredDisk::existsUrl($this->logo()),
		];
	}
}