<?php

namespace App\Http\Resources\Auth\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthProfileResource extends JsonResource{
	protected ?string $token = null;

	public function toArray($request){
		return [
			'name' => $this->name(),
			'email' => $this->email(),
			'mobile' => $this->mobile(),
			'subscription' => [
				'active' => true,
				'plan' => [
					'key' => 2,
					'name' => 'Binge Watch Pack',
					'duration' => [
						'actual' => 90,
						'remaining' => 42,
					],
				],
			],
			'token' => $this->when(!empty($this->token), $this->token),
		];
	}

	public function token(string $token){
		$this->token = $token;
		return $this;
	}
}