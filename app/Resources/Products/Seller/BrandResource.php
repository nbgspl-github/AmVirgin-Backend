<?php

namespace App\Resources\Products\Seller;

use App\Library\Utils\Uploads;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource{
	public function toArray($request){
		return [
			'key' => $this->id(),
			'name' => $this->name(),
			'logo' => Uploads::existsUrl($this->logo()),
		];
	}
}