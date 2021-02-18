<?php

namespace App\Resources\Shop\Customer\HomePage;

use Illuminate\Http\Resources\Json\JsonResource;

class TopPickResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'id' => $this->id,
			'title' => $this->title,
			'description' => $this->description,
			'poster' => $this->poster,
			'type' => $this->type,
		];
	}

	public static function withoutWrapping () : bool
	{
		return true;
	}
}