<?php

namespace App\Http\Controllers\Api\Customer\News\Categories;

use App\Http\Controllers\Api\ApiController;
use App\Models\NewsCategory;
use App\Resources\News\Category\Customer\ListResource as CategoryListResource;
use App\Resources\News\Item\Customer\ListResource as ItemListResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class CategoryController extends ApiController
{
	public function index (): JsonResponse
	{
		$categoryCollection = NewsCategory::all();
		$resourceCollection = CategoryListResource::collection($categoryCollection);
		return responseApp()
			->status($resourceCollection->count() > 0 ? \Illuminate\Http\Response::HTTP_OK : \Illuminate\Http\Response::HTTP_NO_CONTENT)
			->setValue('payload', $resourceCollection)->send();
	}

	public function show ($id): JsonResponse
	{
		$response = responseApp();
		try {
			/**
			 * @var $newsCategory NewsCategory
			 */
			$newsCategory = NewsCategory::query()->whereKey($id)->firstOrFail();
			$newsCollection = $newsCategory->items()->paginate();
			$resourceCollection = ItemListResource::collection($newsCollection);
			$response->status($resourceCollection->count() > 0 ? \Illuminate\Http\Response::HTTP_OK : \Illuminate\Http\Response::HTTP_NO_CONTENT)->setValue('payload', $resourceCollection->response()->getData(true));
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message($exception->getMessage());
		} catch (\Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}