<?php

namespace App\Resources\Shop\Customer\HomePage;

use App\Library\Utils\Extensions\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandsInFocusResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'slug' => null,
			'name' => $this->name,
			'description' => null,
			'products' => 0,
			'icon' => [
				'exists' => false,
				'url' => Str::Empty,
			],
		];
	}
}