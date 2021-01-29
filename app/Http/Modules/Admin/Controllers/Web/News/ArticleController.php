<?php

namespace App\Http\Modules\Admin\Controllers\Web\News;

class ArticleController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index () : \Illuminate\Contracts\Support\Renderable
	{
		return view('admin.news.articles.index')->with('articles',
			$this->paginateWithQuery(\App\Models\News\Article::query()->whereLike('title', $this->queryParameter())->latest())
		);
	}

	public function edit () : \Illuminate\Contracts\Support\Renderable
	{

	}

	public function store () : \Illuminate\Http\RedirectResponse
	{

	}

	public function update () : \Illuminate\Http\RedirectResponse
	{

	}

	public function delete (\App\Models\News\Category $category) : \Illuminate\Http\JsonResponse
	{

	}
}