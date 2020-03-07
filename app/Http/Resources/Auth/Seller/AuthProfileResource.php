<?php

namespace App\Http\Resources\Auth\Seller;

use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthProfileResource extends JsonResource {
	public function toArray($request) {
		return [
			'key' => $this->id,
			'name' => $this->name,
			'businessName' => $this->businessName,
			'description' => $this->description,
			'email' => $this->email,
			'mobile' => $this->mobile,
			'state' => $this->state,
			'city' => $this->city,
			'rating' => $this->rating,
			'alternateMobile' => $this->alternateMobile,
			'avatar' => SecuredDisk::existsUrl($this->avatar),
		];
	}
}