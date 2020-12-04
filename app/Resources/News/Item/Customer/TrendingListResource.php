<?php

namespace App\Resources\News\Item\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class TrendingListResource extends JsonResource
{
	public function toArray ($request)
	{
		return [
			'key' => $this->id,
			'title' => $this->title,
			'uploadedBy' => $this->uploadedBy,
			'uploadedOn' => $this->uploadedOn
		];
	}
}