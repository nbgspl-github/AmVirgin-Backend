<?php

namespace App\Resources\Filters\Customer\Entertainment\Genre;

use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
	public function toArray ($request)
	{
		return [
			'key' => $this->id,
			'name' => $this->name,
		];
	}
}