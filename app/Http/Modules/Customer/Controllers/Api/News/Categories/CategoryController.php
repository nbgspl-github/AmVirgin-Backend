<?php

namespace App\Http\Modules\Customer\Controllers\Api\News\Categories;

use App\Models\News\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function index () : JsonResponse
	{
		return responseApp()->prepare([
			'categories' => \App\Resources\News\Category\Customer\ListResource::collection(Category::all()),
			'articles' => \App\Resources\News\Articles\ArticleCollection::collection(
				\App\Models\News\Article::query()->latest()->orderByDesc('views')->paginate($this->paginationChunk())
			)->response()->getData()
		]);
	}

	public function show (Category $category) : JsonResponse
	{
		return responseApp()->prepare(
			\App\Resources\News\Articles\ArticleCollection::collection(
				$category->items()->latest('published_at')->paginate($this->paginationChunk())
			)->response()->getData()
		);
	}
}