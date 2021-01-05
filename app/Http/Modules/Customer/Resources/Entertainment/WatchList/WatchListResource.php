<?php

namespace App\Http\Modules\Customer\Resources\Entertainment\WatchList;

class WatchListResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->video->id,
			'title' => $this->video->title,
			'type' => $this->video->type,
			'poster' => $this->video->poster,
		];
	}
}