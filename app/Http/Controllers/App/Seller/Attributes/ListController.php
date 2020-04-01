<?php

namespace App\Http\Controllers\App\Seller\Attributes;

use App\Classes\Str;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\Attribute;
use App\Models\AttributeSet;
use App\Models\AttributeSetItem;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class ListController extends ExtendedResourceController{
	protected array $rules;

	public function __construct(){
		parent::__construct();
	}

	public function show(int $categoryId){
		$response = responseApp();
		try {
			$category = Category::retrieveThrows($categoryId);
			$attributeSetItems = $category->attributeSet->items;
			$attributeSetItems->transform(function (AttributeSetItem $attributeSetItem){
				$attribute = $attributeSetItem->attribute;
				return [
					'key' => $attribute->id(),
					'label' => $attribute->name(),
					'description' => $attribute->description(),
					'code' => $attribute->code(),
					'required' => $attribute->required(),
					'predefined' => $attribute->predefined(),
					'useToCreateVariants' => $attribute->useToCreateVariants(),
					'multiValue' => $attribute->multiValue(),
					'minValues' => $attribute->minValues(),
					'maxValues' => $attribute->maxValues(),
					'values' => $attribute->values(),
				];
			});
			$status = $attributeSetItems->count() == 0 ? HttpNoContent : HttpOkay;
			$response->status($status)->message('Listing all attributes for the category.')->setValue('data', $attributeSetItems);
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find attribute for that key.');
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	protected function guard(){
		return auth('seller-api');
	}
}