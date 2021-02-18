<?php

namespace App\Http\Modules\Customer\Controllers\Api\Shop;

use App\Library\Utils\Extensions\Str;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index () : JsonResponse
	{
		$category = Category::startQuery()->isCategory()->get();
		$category->transform(function (Category $category) {
			$subCategory = $category->children()->get();
			$subCategory = $subCategory->transform(function (Category $subCategory) {
				$vertical = $subCategory->children()->get();
				$vertical = $vertical->transform(function (Category $vertical) {
					return [
						'key' => $vertical->id,
						'slug' => null,
						'name' => $vertical->name,
						'type' => $vertical->type,
						'products' => 0,
						'icon' => [
							'exists' => false,
							'url' => Str::Empty,
						],
					];
				});
				return [
					'key' => $subCategory->id,
					'slug' => null,
					'name' => $subCategory->name,
					'type' => $subCategory->type,
					'products' => 0,
					'icon' => [
						'exists' => false,
						'url' => Str::Empty,
					],
					'children' => [
						'available' => $vertical->count() > 0,
						'count' => $vertical->count(),
						'items' => $vertical,
					],
				];
			});
			return [
				'key' => $category->id,
				'slug' => null,
				'name' => $category->name,
				'type' => $category->type,
				'products' => 0,
				'icon' => [
					'exists' => false,
					'url' => Str::Empty,
				],
				'children' => [
					'available' => $subCategory->count() > 0,
					'count' => $subCategory->count(),
					'items' => $subCategory,
				],
			];
		});
		return responseApp()->status($category->count() > 0 ? \Illuminate\Http\Response::HTTP_OK : \Illuminate\Http\Response::HTTP_NO_CONTENT)
			->setValue('data', $category)->message('Listing all available categories.')
			->send();
	}
}