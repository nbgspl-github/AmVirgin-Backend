<?php

namespace App\Resources\Products\Seller;

use App\Models\Attribute;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource{
	protected ?Attribute $originalAttribute;

	public function __construct($resource){
		parent::__construct($resource);
		$this->originalAttribute = $this->attribute;
	}

	public function toArray($request){
		return [
			'key' => $this->id(),
			'value' => $this->value,
			'original' => $this->originalAttribute(),
		];
	}

	private function originalAttribute(){
		return [
			'key' => $this->originalAttribute->id(),
			'label' => $this->originalAttribute->name(),
			'description' => $this->originalAttribute->description(),
			'code' => $this->originalAttribute->code(),
			'required' => $this->originalAttribute->required(),
			'predefined' => $this->originalAttribute->predefined(),
			'useToCreateVariants' => $this->originalAttribute->useToCreateVariants(),
			'combineMultipleValues' => $this->originalAttribute->combineMultipleValues(),
			'multiValue' => $this->originalAttribute->multiValue(),
			'minValues' => $this->originalAttribute->minValues(),
			'maxValues' => $this->originalAttribute->maxValues(),
			'values' => $this->originalAttribute->values(),
		];
	}

}