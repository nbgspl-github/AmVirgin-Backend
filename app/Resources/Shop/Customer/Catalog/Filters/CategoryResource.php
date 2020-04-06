<?php

namespace App\Resources\Shop\Customer\Catalog\Filters;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryResource extends AbstractBuiltInResource{
	public const RequiredColumn = 'categoryId';

	public function toArray($request){
		return [
			'label' => $this->label(),
			'builtIn' => $this->builtIn(),
			'type' => $this->builtInType(),
			'mode' => 'single',
			'options' => $this->values,
		];
	}

	public function withValues(Collection $values): self{
		$this->values = $this->descendants($values->first());
		return $this;
	}

	public function descendants(int $categoryId): array{
		$category = Category::retrieve($categoryId);
		$descendants = $category->descendants();
		dd($category->name());
		return $descendants->transform(function ($item){
			return [
				'key' => $item['categoryId'],
				'name' => $item['name'],
			];
		})->toArray();
	}
}