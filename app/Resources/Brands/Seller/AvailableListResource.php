<?php

namespace App\Resources\Brands\Seller;

use App\Library\Utils\Uploads;
use Illuminate\Http\Resources\Json\JsonResource;

class AvailableListResource extends JsonResource{
	public function toArray($request){
		return [
			'key' => $this->id(),
			'name' => $this->name(),
			'logo' => Uploads::existsUrl($this->logo()),
			'status' => $this->status(),
		];
	}
}