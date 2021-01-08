<?php

namespace App\Resources\Auth\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthProfileResource extends JsonResource
{
	protected ?string $token = null;

	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'name' => $this->name,
			'email' => $this->email,
			'mobile' => $this->mobile,
			'avatar' => $this->avatar,
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

	public function token (string $token) : AuthProfileResource
	{
		$this->token = $token;
		return $this;
	}
}