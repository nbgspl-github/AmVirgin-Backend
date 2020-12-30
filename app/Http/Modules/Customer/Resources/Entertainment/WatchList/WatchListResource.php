<?php

namespace App\Http\Modules\Customer\Resources\Entertainment\WatchList;

class WatchListResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'title' => $this->title,
			'type' => $this->type,
			'poster' => $this->poster,
		];
	}
}