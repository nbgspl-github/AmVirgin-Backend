<?php

namespace App\Resources\News\Item\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'title' => $this->title,
			'image' => $this->image,
			'author' => $this->author,
			'published' => $this->created_at->format('Y-m-d H:i:s')
		];
	}
}