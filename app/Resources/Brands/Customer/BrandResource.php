<?php

namespace App\Resources\Brands\Customer;

use App\Library\Utils\Uploads;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource{
	public function toArray($request){
		return [
			'name' => $this->name,
			'logo' => Uploads::existsUrl($this->logo),
		];
	}

	public static function withoutWrapping(){
		return true;
	}
}