<?php

namespace App\Http\Controllers\App\Seller;

use App\Classes\Str;
use App\Http\Controllers\BaseController;
use App\Models\Category;
use App\Storage\SecuredDisk;
use App\Traits\FluentResponse;
use Sujip\Guid\Facades\Guid;

class CategoriesController extends BaseController{
	use FluentResponse;

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$category = Category::whereQuery()->isCategory()->get();
		$category->transform(function (Category $category){
			$children = $category->children()->get();
			$children = $children->transform(function (Category $subCategory){
				$innerChildren = $subCategory->children()->get();
				$innerChildren = $innerChildren->transform(function (Category $vertical){
					return [
						'id' => $vertical->id(),
						'name' => $vertical->name(),
						'hasIcon' => false,
						'icon' => Str::Empty,
						'type' => $vertical->type(),
					];
				});
				return [
					'id' => $subCategory->id(),
					'name' => $subCategory->name(),
					'hasIcon' => false,
					'icon' => Str::Empty,
					'type' => $subCategory->type(),
					'hasInner' => $innerChildren->count() > 0,
					'count' => $innerChildren->count(),
					'inner' => $innerChildren,
				];
			});
			return [
				'id' => $category->id(),
				'name' => $category->name(),
				'hasIcon' => false,
				'icon' => Str::Empty,
				'type' => $category->type(),
				'hasInner' => $children->count() > 0,
				'count' => $children->count(),
				'inner' => $children,
			];
		});
		return $this->response()->status(HttpOkay)->setValue('data', $category)->message(function () use ($category){
			return sprintf('Found %d categories.', $category->count());
		})->send();
	}
}