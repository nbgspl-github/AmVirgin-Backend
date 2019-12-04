<?php

namespace App\Http\Controllers\Web;

use App\Category;
use App\Http\Controllers\Base\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use stdClass;

class CategoriesController extends WebController {
	public function index() {
		$categories = Category::all();
		return view('admin.categories.index')->with('categories', $categories);
	}

	public function create() {
		$relations = $this->relations();
		return view('admin.categories.create')->with('all', $relations);
	}

	public function edit($id) {
		$category = Category::retrieve($id);
		$relations = $this->relations();
		if ($category != null) {
			return view('admin.categories.edit')->with('category', $category)->with('all', $relations);
		}
		else {
			notify()->error('Could not find a category with that Id.');
			return redirect(route('admin.categories.index'));
		}
	}

	public function show($id) {

	}

	public function update(Request $request, $id = null) {
		$validator = Validator::make($request->all(), [
			'name' => ['bail', 'required', 'string', 'min:1', 'max:100'],
			'parentId' => ['bail', 'required', 'numeric'],
			'description' => ['bail', 'nullable', 'string', 'min:1', 'max:1000'],
			'visibility' => ['bail', 'required', Rule::in([0, 1])],
			'poster' => ['bail', 'nullable', 'mime:jpeg,jpg,bmp,png,webp'],
		]);
		if ($validator->fails()) {
			notify()->error($validator->errors()->first());
			return back()->withInput($request->all());
		}
		else {
			Category::create($request->all());
			notify()->success('Category created successfully.');
			return redirect(route('admin.categories.index'));
		}
	}

	public function store(Request $request) {
		$validator = Validator::make($request->all(), [
			'name' => ['bail', 'required', 'string', 'min:1', 'max:100'],
			'parentId' => ['bail', 'required', 'numeric'],
			'description' => ['bail', 'nullable', 'string', 'min:1', 'max:1000'],
			'visibility' => ['bail', 'required', Rule::in([0, 1])],
			'poster' => ['bail', 'nullable', 'mime:jpeg,jpg,bmp,png,webp'],
		]);
		if ($validator->fails()) {
			notify()->error($validator->errors()->first());
			return back()->withInput($request->all());
		}
		else {
			Category::create($request->all());
			notify()->success('Category created successfully.');
			return redirect(route('admin.categories.index'));
		}
	}

	public static function relations($insertSuper = true) {
		$super = new stdClass();
		$super->id = config('values.category.super.index');
		$super->name = config('values.category.super.name');
		$super->subItems = [];
		$all = [];
		if ($insertSuper) {
			$all[] = $super;
		}
		$topLevel = Category::where('parentId', $super->id)->get();
		$topLevel->each(function (Category $category) use (&$all) {
			$item = new stdClass();
			$item->id = $category->getId();
			$item->name = $category->getName();
			$inner = Category::where('parentId', $category->getId())->get();
			$inner->transform(function (Category $category) {
				$item = new stdClass();
				$item->id = $category->getId();
				$item->name = $category->getName();
				return $item;
			});
			$item->subItems = $inner->all();
			$all[] = $item;
		});
		return $all;
	}
}