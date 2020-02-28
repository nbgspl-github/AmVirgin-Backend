<?php

namespace App\Http\Resources\Auth\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthProfileResource extends JsonResource {
	public function toArray($request) {
		return [
			'name' => $this->name,
			'email' => $this->email,
			'mobile' => $this->mobile,
		];
	}
}