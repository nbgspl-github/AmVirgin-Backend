<?php

namespace App\Http\Controllers\Web\Admin\Products\Attributes;

use App\Classes\Rule;
use App\Classes\Str;
use App\Constants\CustomerAttributeInterfaceType;
use App\Constants\SellerAttributeInterfaceType;
use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Interfaces\Tables;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Traits\ValidatesRequest;
use Throwable;

class AttributesController extends BaseController{
	use ValidatesRequest;

	protected array $rules;

	public function __construct(){
		parent::__construct();
		$this->rules = [
			'store' => [
				'category.*' => ['bail', 'required', Rule::existsPrimary(Tables::Categories)],
				'name' => ['bail', 'required', 'string', 'min:1', 'max:255'],
				'code' => ['bail', 'required', 'string', 'min:1', 'max:255'],
				'sellerInterfaceType' => ['bail', 'required', Rule::in([SellerAttributeInterfaceType::Select, SellerAttributeInterfaceType::Radio, SellerAttributeInterfaceType::Text, SellerAttributeInterfaceType::TextArea])],
				'customerInterfaceType' => ['bail', 'required', Rule::in([CustomerAttributeInterfaceType::Options, CustomerAttributeInterfaceType::Readable])],
				'values' => ['bail', Rule::requiredIf(function (){
					if (Str::equals(request('sellerInterfaceType'), SellerAttributeInterfaceType::Radio) || Str::equals(request('sellerInterfaceType'), SellerAttributeInterfaceType::Select))
						return true;
					else
						return false;
				}), 'string', 'min:2',],
			],
		];
	}

	public function index(){
		$attributes = Attribute::all();
		$categories = Category::all();
		return view('admin.attributes.index')->with('categories', $categories)->with('attributes', $attributes);
	}

	public function create(){
		$categories = $topLevel = Category::where('parentId', 0)->get();
		$topLevel->transform(function (Category $topLevel){
			$children = $topLevel->children()->get();
			$children = $children->transform(function (Category $child){
				$innerChildren = $child->children()->get();
				$innerChildren = $innerChildren->transform(function (Category $inner){
					return [
						'id' => $inner->getKey(),
						'name' => $inner->getName(),
						'popularCategory' => $inner->popularCategory(),
					];
				});
				return [
					'id' => $child->getKey(),
					'name' => $child->getName(),
					'hasInner' => $innerChildren->count() > 0,
					'inner' => $innerChildren,
					'popularCategory' => $child->popularCategory(),
				];
			});
			return [
				'id' => $topLevel->getKey(),
				'name' => $topLevel->getName(),
				'hasInner' => $children->count() > 0,
				'inner' => $children,
				'popularCategory' => $topLevel->popularCategory(),
			];
		});
		return view('admin.attributes.create')->with('categories', $topLevel);
	}

	public function store(){
		$response = responseWeb();
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['store']);
			collect($validated->category)->each(function ($categoryId) use ($validated){
				$attribute = Attribute::where([
					['categoryId', $categoryId],
					['code', $validated->code],
				])->first();
				if ($attribute == null) {
					$attribute = Attribute::create([
						'name' => $validated->name,
						'categoryId' => $categoryId,
						'sellerInterfaceType' => $validated->sellerInterfaceType,
						'customerInterfaceType' => $validated->customerInterfaceType,
						'code' => $validated->code,
						'required' => request()->has('required'),
						'filterable' => request()->has('filterable'),
					]);
					$values = explode('/', $validated->values);
					foreach ($values as $value) {
						AttributeValue::create([
							'attributeId' => $attribute->id,
							'value' => $value,
						]);
					}
				}
			});
			$response->success('Successfully created attribute and related values.')->route('admin.products.attributes.index');
		}
		catch (ValidationException $exception) {
			$response->error($exception->getMessage())->data(request()->all())->back();
		}
		catch (Throwable $exception) {
			$response->error($exception->getMessage())->data(request()->all())->back();
		}
		finally {
			return $response->send();
		}
	}
}