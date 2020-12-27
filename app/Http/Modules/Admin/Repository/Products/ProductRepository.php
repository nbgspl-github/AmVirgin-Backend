<?php

namespace App\Http\Modules\Admin\Repository\Products;

class ProductRepository extends \App\Http\Modules\Shared\Repository\BaseRepository implements Contracts\ProductRepository
{
	public function __construct (\App\Models\Product $model)
	{
		parent::__construct($model);
	}

	public function allProductsPaginated (int $chunk = 15) : \Illuminate\Contracts\Pagination\LengthAwarePaginator
	{
		return $this->model->newQuery()->paginate($chunk);
	}
}