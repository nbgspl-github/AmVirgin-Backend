<?php

namespace App\Http\Resources\Auth\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource {
	public function toArray($request) {
		return [
			'name' => $this->name,
			'email' => $this->email,
		];
	}
}