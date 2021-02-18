<?php

namespace App\Http\Modules\Customer\Resources\Entertainment\Product;

class ImageResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request)
	{
		return \App\Library\Utils\Uploads::existsUrl($this->path);
	}
}