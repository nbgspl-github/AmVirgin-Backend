<?php

namespace App\Resources\Brands\Seller;

use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class AvailableListResource extends JsonResource{
	public function toArray($request){
		return [
			'key' => $this->id(),
			'name' => $this->name(),
			'logo' => SecuredDisk::existsUrl($this->logo()),
			'status' => $this->status(),
		];
	}
}