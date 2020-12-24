<?php

namespace App\Http\Modules\Admin\Controllers\Web;

use App\Exceptions\ValidationException;
use App\Http\Modules\Shared\Controllers\BaseController;
use App\Http\Requests\StoreCategoryFilter;
use App\Library\Enums\Common\Tables;
use App\Library\Utils\Extensions\Arrays;
use App\Library\Utils\Extensions\Rule;
use App\Models\AttributeSetItem;
use App\Models\CatalogFilter;
use App\Models\Category;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Throwable;

class CatalogFilterController extends BaseController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->rules = [
			'store' => [
				'label' => ['bail', 'nullable', 'string', 'min:1', 'max:255'],
				'categoryId' => ['bail', 'required', Rule::existsPrimary(Tables::Categories)->whereNot('type', Category::Types['Root'])],
				'builtInType' => ['bail', 'required_with:builtIn', Rule::in(Arrays::values(CatalogFilter::BuiltInFilters))],
				'attributeId' => ['bail', 'required_without:builtIn', Rule::existsPrimary(Tables::Attributes)],
			],
		];
	}

	public function index ()
	{
		$catalogFilters = CatalogFilter::all();
		return view('admin.filters.catalog.index')->with('filters', $catalogFilters);
	}

	public function create ()
	{
		$roots = Category::startQuery()->isRoot()->get();
		$roots->transform(function (Category $root) {
			$category = $root->children()->orderBy('name')->get();
			$category = $category->transform(function (Category $category) {
				$subCategory = $category->children()->orderBy('name')->get();
				$subCategory = $subCategory->transform(function (Category $subCategory) {
					$vertical = $subCategory->children()->orderBy('name')->get();
					$vertical->transform(function (Category $vertical) {
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
		return view('admin.filters.catalog.create')->with('roots', $roots);
	}

	public function store ()
	{
		$response = responseWeb();
		try {
			$validated = $this->requestValid(request(), $this->rules['store']);
			CatalogFilter::create([
				'label' => $validated['label'],
				'builtIn' => request()->has('builtIn'),
				'builtInType' => request()->has('builtIn') ? $validated['builtInType'] : null,
				'attributeId' => !request()->has('builtIn') ? $validated['attributeId'] : null,
				'categoryId' => $validated['categoryId'],
				'allowMultiValue' => !request()->has('builtIn') ? request()->has('allowMultiValue') : CatalogFilter::AllowMultiValueDefault[$validated['builtInType']],
			]);
			$response->success('Catalog filter created successfully.')->route('admin.filters.catalog.index');
		} catch (ValidationException $exception) {
			$response->error($exception->getMessage())->data(request()->all())->back();
		} catch (Throwable $exception) {
			$response->error($exception->getMessage())->data(request()->all())->back();
		} finally {
			return $response->send();
		}
	}

	public function attributes ($id): JsonResponse
	{
		$response = responseApp();
		try {
			$category = Category::startQuery()->displayable()->key($id)->firstOrFail();
			$attributeSet = $category->attributeSet;
			if ($attributeSet != null) {
				$attributes = $attributeSet->items;
				$attributes->transform(function (AttributeSetItem $item) {
					return $item->attribute;
				});
				$attributes = $attributes->where('predefined', true)->values();
				$response->setValue('options', view('admin.filters.catalog.attributeOptions')->with('attributes', $attributes)->render())->status(\Illuminate\Http\Response::HTTP_OK)->message('Attributes retrieved successfully.');
			} else {
				$response->setValue('options', view('admin.filters.catalog.attributeOptions'))->status(\Illuminate\Http\Response::HTTP_NO_CONTENT)->message('No attribute set found for category.');
			}
		} catch (ModelNotFoundException $exception) {
			$response->setValue('options')->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find category for that key.');
		} catch (Throwable $exception) {
			$response->setValue('options')->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}