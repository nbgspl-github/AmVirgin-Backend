<?php

namespace App\Http\Controllers\Api\Customer\News\Categories;

use App\Http\Controllers\Api\ApiController;
use App\Models\NewsItem;
use App\Resources\News\Item\Customer\ItemResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class NewsController extends ApiController
{
	public function show ($id): JsonResponse
	{
		$response = responseApp();
		try {
			$newsItem = NewsItem::query()->whereKey($id)->firstOrFail();
			$newsResource = new ItemResource($newsItem);
			$response->status(\Illuminate\Http\Response::HTTP_NO_CONTENT)->payload($newsResource);
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->payload();
		} catch (\Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage())->payload();
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}