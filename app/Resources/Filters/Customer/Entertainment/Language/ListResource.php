<?php

namespace App\Resources\Filters\Customer\Entertainment\Language;

use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
	public function toArray ($request)
	{
		return [
			'key' => $this->id,
			'name' => $this->name,
			'code' => $this->code
		];
	}
}