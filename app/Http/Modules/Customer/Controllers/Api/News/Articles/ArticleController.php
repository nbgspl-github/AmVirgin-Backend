<?php

namespace App\Http\Modules\Customer\Controllers\Api\News\Articles;

class ArticleController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function index () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			\App\Resources\News\Articles\ArticleCollection::collection(\App\Models\News\Article::query()->paginate($this->paginationChunk()))->response()->getData()
		);
	}

	public function show (\App\Models\News\Article $article) : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			new \App\Resources\News\Articles\ArticleResource($article)
		);
	}
}