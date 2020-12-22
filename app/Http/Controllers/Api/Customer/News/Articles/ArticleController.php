<?php

namespace App\Http\Controllers\Api\Customer\News\Articles;

class ArticleController extends \App\Http\Controllers\Api\ApiController
{
	public function show (\App\Models\News\Article $article)
	{

	}

	protected function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}