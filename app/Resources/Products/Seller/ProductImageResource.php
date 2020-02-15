<?php

namespace App\Resources\Products\Seller;

use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource{
	public static function withoutWrapping(){
		return true;
	}

	public function toArray($request){
		return [
			'url' => $this->when(SecuredDisk::access()->exists($this->path) == true, SecuredDisk::access()->url($this->path)),
		];
	}
}