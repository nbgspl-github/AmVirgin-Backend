<?php

namespace App\Http\Modules\Seller\Controllers\Api\Attributes;

use App\Models\Category;

class ListController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
	}

	public function show (Category $category) : \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		$attributes = $category->attributeSet->transform(function (\App\Models\Attribute $attribute) {
			return [
				'key' => $attribute->id,
				'label' => $attribute->name,
				'description' => $attribute->description,
				'group' => $attribute->group,
				'code' => $attribute->code,
				'required' => $attribute->required,
				'predefined' => $attribute->predefined,
				'useToCreateVariants' => $attribute->useToCreateVariants,
				'combineMultipleValues' => $attribute->combineMultipleValues,
				'multiValue' => $attribute->multiValue,
				'minValues' => $attribute->minValues,
				'maxValues' => $attribute->maxValues,
				'values' => $attribute->values,
			];
		});
		$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Listing all attributes for the category.')->setValue('data', $attributes);
		return $response->send();
	}
}