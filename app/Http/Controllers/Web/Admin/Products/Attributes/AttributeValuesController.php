<?php

namespace App\Http\Controllers\Web\Admin\Products\Attributes;

use App\Classes\Str;
use App\Classes\WebResponse;
use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class AttributeValuesController extends BaseController{
	use ValidatesRequest;

	protected array $rules;

	public function __construct(){
		parent::__construct();
		$this->rules = [
			'store' => [
				'values' => ['bail', 'required', 'string', 'min:3', 'max:100000'],
			],
			'update' => [

			],
		];
	}

	public function edit($attributeId){
		$response = responseWeb();
		try {
			$attribute = Attribute::retrieveThrows($attributeId);
			if (Str::equals($attribute->sellerInterfaceType(), Attribute::SellerInterfaceType['Select']) || Str::equals($attribute->sellerInterfaceType(), Attribute::SellerInterfaceType['Radio'])) {
				$parents = [];
				$parent = $attribute->category;
				$parents[] = $parent->name;
				while (($parent = $parent->parent) != null) {
					$parents[] = $parent->name;
				}
				$parents = array_reverse($parents);
				$parents = implode(' > ', $parents);
				$values = $attribute->values()->get('value')->transform(fn(AttributeValue $attributeValue) => $attributeValue->value())->toArray();
				$values = implode('/', $values);
				$response = view('admin.attributes.values.edit')->with('attribute', $attribute)->with('parent', $parents)->with('values', $values);
			}
			else {
				$response->error('You can not add values to an attribute marked for input by seller.')->route('admin.products.attributes.index');
			}
		}
		catch (ModelNotFoundException $exception) {
			$response->error($exception->getMessage())->route('admin.products.attributes.index');
		}
		catch (Throwable $exception) {
			$response->error($exception->getMessage())->route('admin.products.attributes.index');
		}
		finally {
			if ($response instanceof WebResponse)
				return $response->send();
			else
				return $response;
		}
	}

	public function store($attributeId){
		$response = responseWeb();
		try {
			$attribute = Attribute::retrieveThrows($attributeId);
			$validated = (object)$this->requestValid(request(), $this->rules['store']);
			AttributeValue::where([
				['attributeId', $attributeId],
				['categoryId', $attribute->categoryId()],
			])->get()->each(fn(AttributeValue $attributeValue) => AttributeValue::destroy($attributeValue->id()));
			$values = explode('/', $validated->values);
			foreach ($values as $value) {
				AttributeValue::create([
					'attributeId' => $attributeId,
					'categoryId' => $attribute->categoryId(),
					'value' => $value,
				]);
			}
			$response->success('Attribute values were modified successfully.')->route('admin.products.attributes.index');
		}
		catch (ValidationException $exception) {
			$response->error('You must provide at-least two values.')->back();
		}
		catch (ModelNotFoundException $exception) {
			$response->error($exception->getMessage())->route('admin.products.attributes.index');
		}
		catch (Throwable $exception) {
			$response->error($exception->getMessage())->route('admin.products.attributes.index');
		}
		finally {
			if ($response instanceof WebResponse)
				return $response->send();
			else
				return $response;
		}
	}
}