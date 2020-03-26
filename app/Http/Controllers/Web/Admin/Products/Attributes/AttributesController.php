<?php

namespace App\Http\Controllers\Web\Admin\Products\Attributes;

use App\Classes\Rule;
use App\Classes\Str;
use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Interfaces\Tables;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\PrimitiveType;
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
				'description' => ['bail', 'required', 'string', 'min:1', 'max:5000'],
				'code' => ['bail', 'required', 'string', 'min:1', 'max:255'],
				'sellerInterfaceType' => ['bail', 'required', Rule::in([Attribute::SellerInterfaceType['Select'], Attribute::SellerInterfaceType['Input'], Attribute::SellerInterfaceType['TextArea'], Attribute::SellerInterfaceType['Radio']])],
				'customerInterfaceType' => ['bail', 'required', Rule::in([Attribute::CustomerInterfaceType['Options'], Attribute::CustomerInterfaceType['Readable']])],
				'genericType' => ['bail', Rule::requiredIf(function (){
					if (Str::equals(request('sellerInterfaceType'), Attribute::SellerInterfaceType['Select']) || Str::equals(request('sellerInterfaceType'), Attribute::SellerInterfaceType['Radio']))
						return true;
					else
						return false;
				}), Rule::in([Attribute::GenericTypes['Number'], Attribute::GenericTypes['DecimalNumber'], Attribute::GenericTypes['Color'], Attribute::GenericTypes['MultiColor'], Attribute::GenericTypes['String'], Attribute::GenericTypes['File'], Attribute::GenericTypes['Other'],])],
				'segmentPriority' => ['bail', 'required', 'numeric', 'min:0', 'max:10'],
			],
		];
	}

	public function index(){
		$attributes = Attribute::all();
		$categories = Category::all();
		return view('admin.attributes.index')->with('categories', $categories)->with('attributes', $attributes);
	}

	public function create(){
		$types = PrimitiveType::all();
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
		return view('admin.attributes.create')->with('categories', $topLevel)->with('types', $types);
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
						'description' => $validated->description,
						'categoryId' => $categoryId,
						'sellerInterfaceType' => $validated->sellerInterfaceType,
						'customerInterfaceType' => $validated->customerInterfaceType,
						'genericType' => $validated->genericType,
						'code' => $validated->code,
						'required' => request()->has('required'),
						'filterable' => request()->has('filterable'),
						'productNameSegment' => request()->has('productNameSegment'),
						'segmentPriority' => $validated->segmentPriority,
					]);
					Attribute::where([
						['categoryId', $categoryId],
						['segmentPriority', $validated->segmentPriority],
					])->update(['segmentPriority', 0]);
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