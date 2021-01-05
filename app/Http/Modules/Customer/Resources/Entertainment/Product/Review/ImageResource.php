<?php

namespace App\Http\Modules\Customer\Resources\Entertainment\Product\Review;

class ImageResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request) : ?string
	{
		return $this->file;
	}
}