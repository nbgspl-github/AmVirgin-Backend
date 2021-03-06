<?php

namespace App\Resources\GlobalSearch;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResultResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'name' => $this->name,
		];
	}
}