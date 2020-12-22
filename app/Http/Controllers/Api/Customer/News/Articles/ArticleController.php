<?php

namespace App\Http\Controllers\Api\Customer\News\Articles;

class ArticleController extends \App\Http\Controllers\Api\ApiController
{
	public function show (\App\Models\News\Article $article) : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			new \App\Resources\News\Articles\ArticleResource($article)
		);
	}

	protected function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}