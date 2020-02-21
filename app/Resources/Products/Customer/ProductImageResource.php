<?php

namespace App\Resources\Products\Customer;

use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource {
	public static function withoutWrapping() {
		return true;
	}

	public function toArray($request) {
		return $this->when(SecuredDisk::access()->exists($this->path) == true, SecuredDisk::access()->url($this->path));
	}
}