<?php

namespace App\Http\Controllers\App\Customer;

use App\Classes\Str;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\Category;
use App\Storage\SecuredDisk;
use App\Traits\FluentResponse;
use Illuminate\Http\JsonResponse;
use Sujip\Guid\Facades\Guid;

class CategoryController extends ExtendedResourceController{
	public function __construct(){
		parent::__construct();
	}

	public function index(): JsonResponse{
		$category = Category::startQuery()->isCategory()->get();
		$category->transform(function (Category $category){
			$subCategory = $category->children()->get();
			$subCategory = $subCategory->transform(function (Category $subCategory){
				$vertical = $subCategory->children()->get();
				$vertical = $vertical->transform(function (Category $vertical){
					return [
						'key' => $vertical->id(),
						'slug' => $vertical->slug(),
						'name' => $vertical->name(),
						'type' => $vertical->type(),
						'products' => $vertical->productCount(),
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
					'products' => $subCategory->productCount(),
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
				'products' => $category->productCount(),
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
		return responseApp()->status($category->count() > 0 ? HttpOkay : HttpNoContent)->setValue('data', $category)->message('Listing all available categories.')->send();
	}

	protected function guard(){
		return auth('seller-api');
	}
}