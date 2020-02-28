<?php

namespace App\Http\Resources\Auth\Seller;

use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthProfileResource extends JsonResource {
	public function toArray($request) {
		return [
			'name' => $this->name,
			'email' => $this->email,
			'mobile' => $this->mobile,
			'avatar' => SecuredDisk::existsUrl($this->avatar),
		];
	}
}