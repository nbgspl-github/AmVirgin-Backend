<?php

namespace App\Resources\Shop\Customer\Catalog\Filters;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryResource extends AbstractBuiltInResource
{
	const COLUMN = 'categoryId';
	const KEY = 'filter_category';
	const TYPE = 'category';
	const MODE = 'multiple';
	const LABEL = 'Category';

	public function withValues (Collection $values) : self
	{
		$this->values = $this->descendants(request('category'));
		return $this;
	}

	public function descendants (int $id) : array
	{
		$category = Category::query()->find($id);
		$descendants = $category->descendants()->where('type', 'vertical');
		return $descendants->transform(function ($item) {
			return [
				'key' => $item['id'],
				'name' => $item['name'],
			];
		})->toArray();
	}
}