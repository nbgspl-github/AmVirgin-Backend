<?php

namespace App\Http\Controllers\Api\Seller\Attributes;

use App\Http\Controllers\Api\ApiController;
use App\Models\AttributeSetItem;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class ListController extends ApiController
{
	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
	}

	public function show (int $categoryId)
	{
		$response = responseApp();
		try {
			$category = Category::retrieveThrows($categoryId);
			$attributeSet = $category->attributeSet;
			if ($attributeSet != null) {
				$attributeSetItems = $attributeSet->items;
				$attributeSetItems->transform(function (AttributeSetItem $attributeSetItem) {
					$attribute = $attributeSetItem->attribute;
					return [
						'key' => $attribute->id(),
						'label' => $attribute->name(),
						'description' => $attribute->description(),
						'group' => $attributeSetItem->group(),
						'code' => $attribute->code(),
						'required' => $attribute->required(),
						'predefined' => $attribute->predefined(),
						'useToCreateVariants' => $attribute->useToCreateVariants(),
						'combineMultipleValues' => $attribute->combineMultipleValues(),
						'multiValue' => $attribute->multiValue(),
						'minValues' => $attribute->minValues(),
						'maxValues' => $attribute->maxValues(),
						'values' => $attribute->values(),
					];
				});
				$status = $attributeSetItems->count() == 0 ? HttpNoContent : HttpOkay;
				$response->status($status)->message('Listing all attributes for the category.')->setValue('data', $attributeSetItems);
			} else {
				$response->status(HttpNoContent)->message('There are no attribute sets defined for that category.')->setValue('data', []);
			}
		} catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth('seller-api');
	}
}