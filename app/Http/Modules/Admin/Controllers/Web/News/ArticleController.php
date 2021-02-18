<?php

namespace App\Http\Modules\Admin\Controllers\Web\News;

use App\Library\Enums\News\Article\Types;

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

	public function edit (\App\Models\News\Article $article) : \Illuminate\Http\RedirectResponse
	{
		if ($article->type->is(Types::Article)) {
			return redirect()->route('admin.news.articles.content.edit', $article->id);
		} else {
			return redirect()->route('admin.news.articles.videos.edit', $article->id);
		}
	}

	public function store () : \Illuminate\Http\RedirectResponse
	{

	}

	public function update () : \Illuminate\Http\RedirectResponse
	{

	}

	public function delete (\App\Models\News\Article $article) : \Illuminate\Http\JsonResponse
	{
		$article->delete();
		return responseApp()->prepare(
			[], \Illuminate\Http\Response::HTTP_OK, 'News article deleted successfully.'
		);
	}
}