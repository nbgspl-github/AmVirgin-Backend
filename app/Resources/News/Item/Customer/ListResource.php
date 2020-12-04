<?php

namespace App\Resources\News\Item\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
	public function toArray ($request)
	{
		return [
			'key' => $this->id,
			'title' => $this->title,
			'image' => $this->image,
			'uploadedBy' => $this->uploadedBy,
			'uploadedOn' => date('Y-m-d H:i:s', strtotime($this->created_at))
		];
	}
}