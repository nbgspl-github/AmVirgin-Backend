<?php

namespace App\Http\Controllers\App\Customer\News\Categories;

use App\Http\Controllers\AppController;
use App\Models\NewsCategory;
use App\Resources\News\Category\Customer\ListResource as CategoryListResource;
use App\Resources\News\Item\Customer\ListResource as ItemListResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class CategoryController extends AppController
{
	public function index (): JsonResponse
	{
		$categoryCollection = NewsCategory::all();
		$resourceCollection = CategoryListResource::collection($categoryCollection);
		return responseApp()
			->status($resourceCollection->count() > 0 ? HttpOkay : HttpNoContent)
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
			$response->status($resourceCollection->count() > 0 ? HttpOkay : HttpNoContent)->setValue('payload', $resourceCollection->response()->getData(true));
		} catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message($exception->getMessage());
		} catch (\Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::CustomerAPI);
	}
}