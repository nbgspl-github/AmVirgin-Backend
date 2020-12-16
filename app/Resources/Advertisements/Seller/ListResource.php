<?php

namespace App\Resources\Advertisements\Seller;

use App\Library\Utils\Extensions\Time;
use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
	public function toArray ($request): array
	{
		return [
			'key' => $this->id,
			'subject' => $this->subject,
			'message' => $this->message,
			'image' => $this->banner,
			'active' => $this->active,
			'created' => Time::mysqlStamp(strtotime($this->created_at)),
			'status' => $this->status
		];
	}
}