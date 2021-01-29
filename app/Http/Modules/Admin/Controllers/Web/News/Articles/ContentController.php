<?php

namespace App\Http\Modules\Admin\Controllers\Web\News\Articles;

class ContentController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function create () : \Illuminate\Contracts\Support\Renderable
	{
		return view('admin.news.articles.content.create')->with('categories',
			\App\Models\News\Category::query()->get(['id', 'name'])
		);
	}

	public function edit (\App\Models\News\Article $article) : \Illuminate\Contracts\Support\Renderable
	{

	}

	public function store () : \Illuminate\Http\RedirectResponse
	{

	}

	public function update (\Illuminate\Http\Request $request, \App\Models\News\Article $article)
	{

	}
}