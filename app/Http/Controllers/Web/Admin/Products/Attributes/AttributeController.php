<?php

namespace App\Http\Controllers\Web\Admin\Products\Attributes;

use App\Classes\Arrays;
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
use Sujip\Guid\Facades\Guid;
use Throwable;

class AttributeController extends BaseController{
	use ValidatesRequest;

	protected array $rules;

	public function __construct(){
		parent::__construct();
		$this->rules = [
			'store' => [
				'categoryId.*' => ['bail', 'required', Rule::existsPrimary(Tables::Categories)],
				'name' => ['bail', 'required', 'string', 'min:1', 'max:255'],
				'description' => ['bail', 'required', 'string', 'min:1', 'max:5000'],
				'maxValues' => ['bail', 'required_with:multiValue,on', 'numeric', 'min:2', 'max:10000'],
				'minimum' => ['bail', 'required_with:bounded,on', 'numeric', 'lt:maximum'],
				'maximum' => ['bail', 'required_with:bounded,on', 'numeric', 'gt:minimum'],
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
					];
				});
				return [
					'id' => $child->getKey(),
					'name' => $child->getName(),
					'hasInner' => $innerChildren->count() > 0,
					'inner' => $innerChildren,
				];
			});
			return [
				'id' => $topLevel->getKey(),
				'name' => $topLevel->getName(),
				'hasInner' => $children->count() > 0,
				'inner' => $children,
			];
		});
		return view('admin.attributes.create')->with('categories', $topLevel)->with('types', $types);
	}

	public function store(){
		/**
		 * Guidelines to deal with attribute inheritance and how to handle values of such attributes.
		 * 1.) Let us suppose two categories Footwear and Casual.
		 * 2.) Footwear is the parent and Casual is child.
		 * 3.) Casual inherits all attributes of Footwear [Size and Color].
		 * 4.) If Size and Color have predefined set of values and Casual does not provide its own set of values,
		 *      we'll use the values of the parent category
		 */
		$response = responseWeb();
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['store']);
			$attribute = Attribute::startQuery()->code(Str::slug($validated->name))->first();
			if ($attribute == null) {
				$attribute = Attribute::create([
					'name' => $validated->name,
					'description' => $validated->description,
					'sellerInterfaceType' => $validated->sellerInterfaceType,
					'customerInterfaceType' => $validated->customerInterfaceType,
					'primitiveType' => $validated->primitiveType ?? Str::Empty,
					'code' => sprintf('%s', Str::slug($validated->name)),
					'required' => request()->has('required'),
					'filterable' => request()->has('filterable'),
					'productNameSegment' => request()->has('productNameSegment'),
					'segmentPriority' => $validated->segmentPriority,
					'bounded' => request()->has('bounded'),
					'multiValue' => request()->has('multiValue'),
					'maxValues' => request()->has('multiValue') ? $validated->maxValues : 0,
					'minimum' => request()->has('bounded') ? $validated->minimum : 0,
					'maximum' => request()->has('bounded') ? $validated->maximum : 0,
				]);
				$conflict = Attribute::startQuery()->segmentPriority($validated->segmentPriority)->first();
				if (!empty($conflict)) $conflict->update(['segmentPriority', 0]);
			}
			$response->success('Successfully created attribute.')->route('admin.products.attributes.index');
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