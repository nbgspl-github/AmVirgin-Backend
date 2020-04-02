<?php

namespace App\Resources\Products\Customer;

use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductBrandResource extends JsonResource{
	public function toArray($request){
		return [
			'key' => $this->id(),
			'name' => $this->name(),
			'logo' => SecuredDisk::existsUrl($this->logo()),
		];
	}
}