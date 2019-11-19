<?php

namespace App\Http\Controllers\App;

use App\Category;
use App\Http\Controllers\Base\AppController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoriesController extends AppController {
	public function index($id = null) {
		if ($id == null) {
			$categories = Category::all();
			return view('categories.list')->with('categories', $categories);
		} else {
			$categories = Category::all();
			return view('categories.list')->with('categories', $categories);
		}
	}

	public function create() {
		return view('categories.add')->with('categories', Category::all());
	}

	public function store(Request $request) {
		$validator = Validator::make($request->all(), [
			'slug' => ['bail', 'required', 'string', 'min:1', 'max:100'],
			'parent_id' => ['bail', 'nullable'],
			'description' => ['bail', 'nullable', 'string', 'min:1', 'max:1000'],
			'keywords' => ['bail', 'required', 'string', 'min:2', 'max:256'],
			'order' => ['bail', 'required', 'numeric', 'min:1', 'max:100'],
			'homepage_order' => ['bail', 'required', 'numeric', 'min:1', 'max:100'],
			'visibility' => ['bail', 'required', Rule::in([0, 1])],
			'homepage_visible' => ['bail', 'required', Rule::in([0, 1])],
			'navigation_visible' => ['bail', 'required', Rule::in([0, 1])],
			'image_1' => ['bail', 'nullable', 'mime:jpeg,jpg,bmp,png,webp'],
			'image_2' => ['bail', 'nullable', 'mime:jpeg,jpg,bmp,png,webp'],
		]);
		if ($validator->fails()) {
			flash($validator->errors()->first())->error()->important();
			return redirect(route('categories.forms.add'));
		} else {
			Category::create($request->all());
			flash('Category created successfully.')->success()->important();
			return redirect(route('categories.all'));
		}
	}
}