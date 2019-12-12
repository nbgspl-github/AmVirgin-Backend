<?php

namespace App\Http\Resources\Attributes;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource{
	/**
	 * Transform the resource into an array.
	 * @param Request $request
	 * @return array
	 */
	public function toArray($request){
		return [
			'id' => $this->id,
			'name' => $this->name,
			'categoryId' => $this->categoryId,
		];
	}
}