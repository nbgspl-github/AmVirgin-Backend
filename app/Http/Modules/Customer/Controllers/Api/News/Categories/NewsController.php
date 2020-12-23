<?php

namespace App\Http\Modules\Customer\Controllers\Api\News\Categories;

use App\Models\NewsItem;
use App\Resources\News\Item\Customer\ItemResource;
use Illuminate\Http\JsonResponse;

class NewsController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
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