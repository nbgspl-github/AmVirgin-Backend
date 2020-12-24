<?php

namespace App\Http\Modules\Customer\Controllers\Api\News\Categories;

use App\Models\NewsCategory;
use App\Resources\News\Category\Customer\ListResource as CategoryListResource;
use App\Resources\News\Item\Customer\ListResource as ItemListResource;
use Illuminate\Http\JsonResponse;

class CategoryController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function index () : JsonResponse
	{
		$categories = CategoryListResource::collection(NewsCategory::all())->toArray(null);
		\App\Library\Utils\Extensions\Arrays::push($categories, [
			'key' => -1,
			'name' => 'Articles',
			'description' => 'Get all articles'
		]);
		return responseApp()->prepare([
			'categories' => $categories,
			'articles' => \App\Resources\News\Articles\ArticleCollection::collection(\App\Models\News\Article::query()->paginate($this->paginationChunk()))->response()->getData()
		]);
	}

	public function show (NewsCategory $category) : JsonResponse
	{
		return responseApp()->prepare(
			ItemListResource::collection($category->items()->paginate($this->paginationChunk()))->response()->getData()
		);
	}
}