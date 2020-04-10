<?php

namespace App\Resources\Products\Customer;

use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource{
	public static function withoutWrapping(){
		return true;
	}

	public function toArray($request){
		return [
			'link' => SecuredDisk::access()->url($this->path),
		];
	}
}