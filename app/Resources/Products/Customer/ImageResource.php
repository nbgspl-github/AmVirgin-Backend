<?php

namespace App\Resources\Products\Customer;

use App\Library\Utils\Uploads;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
	public function toArray ($request)
	{
		return $this->path;
	}
}