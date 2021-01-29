<?php

namespace App\Http\Modules\Admin\Controllers\Web\News;

use App\Http\Modules\Admin\Requests\News\Category\StoreRequest;
use App\Http\Modules\Admin\Requests\News\Category\UpdateRequest;

class CategoryController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index () : \Illuminate\Contracts\Support\Renderable
	{
		return view('admin.news.categories.index')->with('categories',
			$this->paginateWithQuery(\App\Models\News\Category::query()->whereLike('name', $this->queryParameter())->latest())
		);
	}

	public function create () : \Illuminate\Contracts\Support\Renderable
	{
		return view('admin.news.categories.create');
	}

	public function edit (\App\Models\News\Category $category) : \Illuminate\Contracts\Support\Renderable
	{
		return view('admin.news.categories.edit')->with('category', $category);
	}

	public function store (StoreRequest $request) : \Illuminate\Http\RedirectResponse
	{
		\App\Models\News\Category::query()->create($request->validated());
		return redirect()->route('admin.news.categories.index')->with('success', 'News category created successfully.');
	}

	public function update (UpdateRequest $request, \App\Models\News\Category $category) : \Illuminate\Http\RedirectResponse
	{
		$category->update($request->validated());
		return redirect()->route('admin.news.categories.index')->with('success', 'News category updated successfully.');
	}

	/**
	 * @param \App\Models\News\Category $category
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Exception
	 */
	public function delete (\App\Models\News\Category $category) : \Illuminate\Http\JsonResponse
	{
		$category->delete();
		return responseApp()->prepare(
			[], \Illuminate\Http\Response::HTTP_OK, 'News category deleted successfully.'
		);
	}
}