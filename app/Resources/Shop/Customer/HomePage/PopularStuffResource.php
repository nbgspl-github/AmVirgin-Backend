<?php

namespace App\Resources\Shop\Customer\HomePage;

use App\Library\Utils\Extensions\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class PopularStuffResource extends JsonResource{
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