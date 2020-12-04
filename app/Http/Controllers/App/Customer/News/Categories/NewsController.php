<?php

namespace App\Http\Controllers\App\Customer\News\Categories;

use App\Http\Controllers\AppController;
use App\Models\NewsItem;
use App\Resources\News\Item\Customer\ItemResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class NewsController extends AppController
{
	public function show ($id): JsonResponse
	{
		$response = responseApp();
		try {
			$newsItem = NewsItem::query()->whereKey($id)->firstOrFail();
			$newsResource = new ItemResource($newsItem);
			$response->status(HttpNoContent)->setPayload($newsResource);
		} catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->setPayload();
		} catch (\Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage())->setPayload();
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::CustomerAPI);
	}
}