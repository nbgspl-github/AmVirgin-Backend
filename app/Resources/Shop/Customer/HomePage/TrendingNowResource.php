<?php

namespace App\Resources\Shop\Customer\HomePage;

use App\Classes\Str;
use App\Storage\SecuredDisk;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TrendingNowResource extends JsonResource{
	public function toArray($request){
		return [
			'key' => $this->id(),
			'slug' => $this->slug(),
			'name' => $this->name(),
			'description' => $this->description(),
			'products' => $this->productCount(),
			'icon' => [
				'exists' => false,
				'url' => Str::Empty,
			],
		];
	}

	public static function withoutWrapping(){
		return true;
	}
}