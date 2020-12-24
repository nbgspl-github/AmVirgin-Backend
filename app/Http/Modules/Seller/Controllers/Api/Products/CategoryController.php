<?php

namespace App\Http\Modules\Seller\Controllers\Api\Products;

use App\Library\Utils\Extensions\Str;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
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
						'key' => $vertical->id(),
						'slug' => $vertical->slug(),
						'name' => $vertical->name(),
						'type' => $vertical->type(),
						'icon' => [
							'exists' => false,
							'url' => Str::Empty,
						],
					];
				});
				return [
					'key' => $subCategory->id(),
					'slug' => $subCategory->slug(),
					'name' => $subCategory->name(),
					'type' => $subCategory->type(),
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
				'key' => $category->id(),
				'slug' => $category->slug(),
				'name' => $category->name(),
				'type' => $category->type(),
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
		return responseApp()->status($category->count() > 0 ? \Illuminate\Http\Response::HTTP_OK : \Illuminate\Http\Response::HTTP_NO_CONTENT)->setValue('data', $category)->message('Listing all available categories.')->send();
	}
}