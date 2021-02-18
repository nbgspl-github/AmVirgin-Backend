<?php

namespace App\Resources\News\Category\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'name' => $this->name,
			'description' => $this->description
		];
	}
}