<?php

namespace App\Http\Controllers\Web\Admin\Products\Attributes;

use App\Classes\Arrays;
use App\Classes\Rule;
use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Interfaces\Tables;
use App\Models\Attribute;
use App\Models\AttributeSet;
use App\Models\AttributeSetItem;
use App\Models\Category;
use App\Traits\ValidatesRequest;
use Throwable;

class AttributeSetController extends BaseController{
	use ValidatesRequest;
	protected array $rules;

	public function __construct(){
		parent::__construct();
		$this->rules = [
			'store' => [
				'name' => ['bail', 'required', 'string', 'min:1', 'max:255'],
				'categoryId' => ['bail', 'required', Rule::existsPrimary(Tables::Categories)],
				'selected' => ['bail', 'required'],
				'groups' => ['bail', 'required'],
			],
		];
	}

	public function index(){
		$attributeSets = AttributeSet::all();
		return view('admin.attributes.sets.index')->with('sets', $attributeSets);
	}

	public function create(){
		$attributes = Attribute::startQuery()->orderByAscending('name')->get();
		$roots = Category::startQuery()->isRoot()->get();
		$roots->transform(function (Category $root){
			$category = $root->children()->orderBy('name')->get();
			$category = $category->transform(function (Category $category){
				$subCategory = $category->children()->orderBy('name')->get();
				$subCategory = $subCategory->transform(function (Category $subCategory){
					$vertical = $subCategory->children()->orderBy('name')->get();
					$vertical->transform(function (Category $vertical){
						return [
							'key' => $vertical->id(),
							'name' => $vertical->name(),
							'type' => $vertical->type(),
						];
					});
					return [
						'key' => $subCategory->id(),
						'name' => $subCategory->name(),
						'type' => $subCategory->type(),
						'children' => [
							'available' => $vertical->count() > 0,
							'count' => $vertical->count(),
							'items' => $vertical,
						],
					];
				});
				return [
					'key' => $category->id(),
					'name' => $category->name(),
					'type' => $category->type(),
					'children' => [
						'available' => $subCategory->count() > 0,
						'count' => $subCategory->count(),
						'items' => $subCategory,
					],
				];
			});
			return [
				'key' => $root->id(),
				'name' => $root->name(),
				'type' => $root->type(),
				'children' => [
					'available' => $category->count() > 0,
					'count' => $category->count(),
					'items' => $category,
				],
			];
		});
		return view('admin.attributes.sets.create')->with('attributes', $attributes)->with('roots', $roots);
	}

	public function store(){
		$response = responseWeb();
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['store']);
			$attributeSet = AttributeSet::create([
				'name' => $validated->name,
				'categoryId' => $validated->categoryId,
			]);
			$index = 0;
			Arrays::each($validated->selected, function ($attributeId) use ($attributeSet, $validated, &$index){
				AttributeSetItem::updateOrCreate(
					[
						'attributeSetId' => $attributeSet->id(),
						'attributeId' => $attributeId,
					],
					[
						'attributeSetId' => $attributeSet->id(),
						'attributeId' => $attributeId,
						'group' => $validated->groups[$index++],
					]
				);
			});
			$response->success('Attribute set created successfully.')->route('admin.attributes.sets.index');
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