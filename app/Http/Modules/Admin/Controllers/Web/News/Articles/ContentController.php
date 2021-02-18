<?php

namespace App\Http\Modules\Admin\Controllers\Web\News\Articles;

use App\Http\Modules\Admin\Requests\News\Article\Content\StoreRequest;
use App\Http\Modules\Admin\Requests\News\Article\Content\UpdateRequest;

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
		return view('admin.news.articles.content.edit')->with('article', $article)->with('categories',
			\App\Models\News\Category::query()->get(['id', 'name'])
		);
	}

	public function store (StoreRequest $request) : \Illuminate\Http\RedirectResponse
	{
		\App\Models\News\Article::query()->create($request->validated());
		return redirect()->route('admin.news.articles.index')->with('success', 'Content article created successfully.');
	}

	public function update (UpdateRequest $request, \App\Models\News\Article $article) : \Illuminate\Http\RedirectResponse
	{
		$article->update($request->validated());
		return redirect()->route('admin.news.articles.index')->with('success', 'Content article updated successfully.');
	}
}