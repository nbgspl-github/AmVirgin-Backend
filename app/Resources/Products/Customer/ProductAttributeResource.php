<?php

namespace App\Resources\Products\Customer;

use App\Models\Attribute;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAttributeResource extends JsonResource{
	protected Attribute $attributeDetails;

	public function __construct($resource){
		parent::__construct($resource);
		$this->attributeDetails = $this->attribute;
	}

	public function toArray($request){
		return [
			'label' => $this->attributeDetails->name(),
			'value' => $this->value,
		];
	}
}