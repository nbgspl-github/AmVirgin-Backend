<?php
namespace App\Http\Controllers\App\Seller;
use App\Models\Category;
use App\Http\Controllers\BaseController;
use App\Traits\FluentResponse;

class CategoriesController extends BaseController{
	use FluentResponse;

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$categories = Category::all();
		$categories->transform(function (Category $category){
			return [
				'id' => $category->getKey(),
				'name' => $category->getName(),
			];
		});

		
		return $this->response()->status(HttpOkay)->setValue('data', $categories)->message(function () use ($categories){
			return sprintf('Found %d categories.', $categories->count());
		})->send();
	}
}
