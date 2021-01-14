<?php

namespace App\Http\Modules\Admin\Controllers\Web\Products\Attributes;

use App\Exceptions\ValidationException;
use App\Http\Modules\Shared\Controllers\BaseController;
use App\Library\Utils\Extensions\Arrays;
use App\Models\Attribute;
use App\Models\AttributeSet;
use App\Models\Category;
use App\Traits\ValidatesRequest;
use Throwable;

class AttributeSetController extends BaseController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->rules = [
			'store' => [
				'categoryId' => ['bail', 'required', Category::exists()],
				'selected' => ['bail', 'required'],
			],
		];
	}

	public function index ()
	{
		$attributeSets = AttributeSet::all();
		return view('admin.attributes.sets.index')->with('sets', $attributeSets);
	}

	public function create ()
	{
		$attributes = Attribute::startQuery()->orderByAscending('name')->get();
		$roots = Category::startQuery()->isRoot()->get();
		$roots->transform(function (Category $root) {
			$category = $root->children()->orderBy('name')->get();
			$category = $category->transform(function (Category $category) {
				$subCategory = $category->children()->orderBy('name')->get();
				$subCategory = $subCategory->transform(function (Category $subCategory) {
					$vertical = $subCategory->children()->orderBy('name')->get();
					$vertical->transform(function (Category $vertical) {
						return [
							'key' => $vertical->id,
							'name' => $vertical->name,
							'type' => $vertical->type,
						];
					});
					return [
						'key' => $subCategory->id,
						'name' => $subCategory->name,
						'type' => $subCategory->type,
						'children' => [
							'available' => $vertical->count() > 0,
							'count' => $vertical->count(),
							'items' => $vertical,
						],
					];
				});
				return [
					'key' => $category->id,
					'name' => $category->name,
					'type' => $category->type,
					'children' => [
						'available' => $subCategory->count() > 0,
						'count' => $subCategory->count(),
						'items' => $subCategory,
					],
				];
			});
			return [
				'key' => $root->id,
				'name' => $root->name,
				'type' => $root->type,
				'children' => [
					'available' => $category->count() > 0,
					'count' => $category->count(),
					'items' => $category,
				],
			];
		});
		return view('admin.attributes.sets.create')->with('attributes', $attributes)->with('roots', $roots);
	}

	public function store ()
	{
		$response = responseWeb();
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['store']);
			Arrays::each($validated->selected, function ($attributeId) use ($validated) {
				AttributeSet::query()->updateOrCreate(
					[
						'category_id' => $validated->categoryId,
						'attribute_id' => $attributeId,
					],
					[
						'category_id' => $validated->categoryId,
						'attribute_id' => $attributeId,
					]
				);
			});
			$response->success('Attribute set created successfully.')->route('admin.attributes.sets.index');
		} catch (ValidationException $exception) {
			$response->error($exception->getMessage())->data(request()->all())->back();
		} catch (Throwable $exception) {
			$response->error($exception->getMessage())->data(request()->all())->back();
		} finally {
			return $response->send();
		}
	}
}