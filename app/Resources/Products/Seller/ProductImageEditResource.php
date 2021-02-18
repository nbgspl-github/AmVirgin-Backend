<?php

namespace App\Resources\Products\Seller;

use App\Library\Utils\Uploads;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageEditResource extends JsonResource{
	public static function withoutWrapping(){
		return true;
	}

	public function toArray($request){
		return [
			'id' => $this->id,
			'url' => $this->when(Uploads::access()->exists($this->path) == true, Uploads::access()->url($this->path)),
		];
	}
}