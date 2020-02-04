<?php

namespace App\Http\Controllers\App\Seller;

use App\Classes\Str;
use App\Http\Controllers\BaseController;
use App\Models\Category;
use App\Storage\SecuredDisk;
use App\Traits\FluentResponse;

class CategoriesController extends BaseController{
	use FluentResponse;

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$topLevel = Category::where('parentId', 0)->get();
		$topLevel->transform(function (Category $topLevel){
			$children = $topLevel->children()->get();
			$children = $children->transform(function (Category $child){
				$innerChildren = $child->children()->get();
				$innerChildren = $innerChildren->transform(function (Category $inner){
					return [
						'id' => $inner->getKey(),
						'name' => $inner->getName(),
						'hasIcon' => SecuredDisk::access()->exists($inner->getIcon()),
						'icon' => SecuredDisk::access()->exists($inner->getIcon()) ? SecuredDisk::access()->url($inner->getIcon()) : Str::Empty,
					];
				});
				return [
					'id' => $child->getKey(),
					'name' => $child->getName(),
					'hasIcon' => SecuredDisk::access()->exists($child->getIcon()),
					'icon' => SecuredDisk::access()->exists($child->getIcon()) ? SecuredDisk::access()->url($child->getIcon()) : Str::Empty,
					'hasInner' => $innerChildren->count() > 0,
					'inner' => $innerChildren,
				];
			});
			return [
				'id' => $topLevel->getKey(),
				'name' => $topLevel->getName(),
				'hasIcon' => SecuredDisk::access()->exists($topLevel->getIcon()),
				'icon' => SecuredDisk::access()->exists($topLevel->getIcon()) ? SecuredDisk::access()->url($topLevel->getIcon()) : Str::Empty,
				'hasInner' => $children->count() > 0,
				'inner' => $children,
			];
		});
		return $this->response()->status(HttpOkay)->setValue('data', $topLevel)->message(function () use ($topLevel){
			return sprintf('Found %d categories.', $topLevel->count());
		})->send();
	}
}