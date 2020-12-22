<?php

namespace App\Http\Controllers\Api\Customer\News\Categories;

use App\Http\Controllers\Api\ApiController;
use App\Models\NewsItem;
use App\Resources\News\Item\Customer\ItemResource;
use Illuminate\Http\JsonResponse;

class NewsController extends ApiController
{
	public function show (NewsItem $item) : JsonResponse
	{
		return responseApp()->prepare(
			new ItemResource($item)
		);
	}

	protected function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}