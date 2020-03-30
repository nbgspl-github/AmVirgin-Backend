<?php

namespace App\Http\Controllers\App\Seller\Attributes;

use App\Classes\Str;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\Attribute;
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
			$attributes = $category->attributes;
			$attributes->transform(function (Attribute $attribute){
				$sellerInterfaceType = $attribute->sellerInterfaceType();
				$hasValues =
					Str::equals($sellerInterfaceType, Attribute::SellerInterfaceType['DropDown'])
					|| Str::equals($sellerInterfaceType, Attribute::SellerInterfaceType['Radio']);

				return [
					'key' => $attribute->id(),
					'name' => $attribute->name(),
					'description' => $attribute->description(),
					'code' => $attribute->code(),
					'sellerInterfaceType' => $attribute->sellerInterfaceType(),
					'required' => $attribute->required(),
					'multiValue' => $attribute->multiValue(),
					'maxValues' => $attribute->maxValues(),
					'bounded' => $attribute->bounded(),
					'minimum' => $attribute->bounded() && !$hasValues ? __cast($attribute->minimum(), $attribute->primitiveType) : $attribute->minimum(),
					'maximum' => $attribute->bounded() && !$hasValues ? __cast($attribute->maximum(), $attribute->primitiveType) : $attribute->maximum(),
					'hasValues' => $hasValues,
					'hasType' => !$hasValues,
					'type' => !$hasValues ? $attribute->primitiveType : null,
				];
			});
			$status = $attributes->count() == 0 ? HttpNoContent : HttpOkay;
			$response->status($status)->message('Listing all attributes for the category.')->setValue('data', $attributes);
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