<?php

namespace App\Resources\Brands\Customer;

use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource{
	public function toArray($request){
		return [
			'name' => $this->name,
			'logo' => SecuredDisk::existsUrl($this->logo),
		];
	}

	public static function withoutWrapping(){
		return true;
	}
}