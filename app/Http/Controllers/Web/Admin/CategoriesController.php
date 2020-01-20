<?php

namespace App\Http\Controllers\Web\Admin;

use App\Category;
use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Interfaces\Directories;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use stdClass;
use Throwable;

class CategoriesController extends BaseController{
	use ValidatesRequest;
	use FluentResponse;

	public function __construct(){
		parent::__construct();
		$this->ruleSet->load('rules.admin.categories');
	}

	public static function relations($insertSuper = true){
		$super = new stdClass();
		$super->id = config('values.category.super.index');
		$super->name = config('values.category.super.name');
		$super->subItems = [];
		$all = [];
		if ($insertSuper) {
			$all[] = $super;
		}
		$topLevel = Category::where('parentId', $super->id)->get();
		$topLevel->each(function (Category $category) use (&$all){
			$item = new stdClass();
			$item->id = $category->getKey();
			$item->name = $category->getName();
			$inner = Category::where('parentId', $category->getKey())->get();
			$inner->transform(function (Category $category){
				$item = new stdClass();
				$item->id = $category->getKey();
				$item->name = $category->getName();
				return $item;
			});
			$item->subItems = $inner->all();
			$all[] = $item;
		});
		return $all;
	}

	public function index(){
		$categories = Category::all();
		return view('admin.categories.index')->with('categories', $categories);
	}

	public function create(){
		$relations = $this->relations();
		return view('admin.categories.create')->with('all', $relations);
	}

	public function edit($id){
		$category = Category::retrieve($id);
		$relations = $this->relations();
		if ($category != null) {
			return view('admin.categories.edit')->with('category', $category)->with('all', $relations);
		}
		else {
			return responseWeb()->route('admin.categories.index')->error('Could not find a category with that key.')->send();
		}
	}

	public function show($id){

	}

	public function update(Request $request, $id = null){
		$response = null;
		try {
			$category = Category::retrieveThrows($id);
			$payload = $this->requestValid($request, $this->rules('update'));
			if ($request->has('poster')) {
				$payload['poster'] = Storage::disk('public')->putFile(Directories::Categories, $request->file('poster'), 'public');
			}
			$category->update($payload);
			$response = responseWeb()->route('admin.categories.index')->success('Updated category successfully.');
		}
		catch (ModelNotFoundException $exception) {
			$response = responseWeb()->route('admin.categories.index')->error('Could not find category for that key.');
		}
		catch (ValidationException $exception) {
			$response = responseWeb()->back()->data($request->all())->error($exception->getError());
		}
		catch (Throwable $exception) {
			$response = responseWeb()->back()->data($request->all())->error($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function store(Request $request){
		$response = null;
		try {
			$payload = $this->requestValid($request, $this->rules('store'));
			$payload['poster'] = $request->hasFile('poster') ? Storage::disk('public')->putFile(Directories::Categories, $request->file('poster'), 'public') : null;
			Category::create($payload);
			$response = responseWeb()->route('admin.categories.index')->success('Created category successfully.');
		}
		catch (ValidationException $exception) {
			$response = responseWeb()->back()->data($request->all())->error($exception->getError());
		}
		catch (Throwable $exception) {
			$response = responseWeb()->back()->data($request->all())->error($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function delete($id){
		try {
			$category = Category::retrieveThrows($id);
			$category->delete();
			return $this->success()->message('Category deleted successfully.')->status(HttpOkay)->send();
		}
		catch (ModelNotFoundException $exception) {
			return $this->failed()->message($exception->getMessage())->status(HttpResourceNotFound)->send();
		}
	}
}