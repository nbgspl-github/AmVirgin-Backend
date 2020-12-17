<?php

namespace App\Resources\Products\Customer;

use App\Library\Utils\Uploads;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource{
	public static function withoutWrapping(){
		return true;
	}

	public function toArray($request)
	{
		return $this->when(Uploads::access()->exists($this->path) == true, Uploads::access()->url($this->path));
	}
}