<?php

namespace App\Resources\Auth\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthProfileResource extends JsonResource
{
	protected ?string $token = null;

	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'name' => $this->name,
			'businessName' => $this->businessName,
			'description' => $this->description,
			'email' => $this->email,
			'mobile' => $this->mobile,
			'country' => new CountryResource($this->country),
			'state' => new StateResource($this->state),
			'city' => new CityResource($this->city),
			'pinCode' => $this->pinCode,
			'rating' => $this->rating,
			'address' => [
				'firstLine' => $this->addressFirstLine,
				'secondLine' => $this->addressSecondLine,
			],
			'avatar' => $this->avatar,
			'statistics' => [
				'brands' => [
					'approved' => 1,
					'requested' => 3,
					'rejected' => 2,
				],
				'products' => [
					'total' => $this->productsAdded,
					'sold' => $this->productsSold,
				],
				'sales' => [
					'total' => mt_rand(400000, 500000),
					'today' => mt_rand(10000, 100000),
					'lastWeek' => mt_rand(100000, 200000),
					'lastMonth' => mt_rand(200000, 300000),
					'lastYear' => mt_rand(9000000, 10000000),
				],
			],
			'token' => $this->when(!empty($this->token), $this->token),
		];
	}

	public function token (?string $token) : AuthProfileResource
	{
		$this->token = $token;
		return $this;
	}
}