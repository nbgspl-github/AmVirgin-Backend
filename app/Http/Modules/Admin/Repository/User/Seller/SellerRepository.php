<?php

namespace App\Http\Modules\Admin\Repository\User\Seller;

class SellerRepository extends \App\Http\Modules\Shared\Repository\BaseRepository implements \App\Http\Modules\Admin\Repository\User\Customer\Contracts\CustomerRepository
{
	public function __construct (\App\Models\Auth\Seller $model)
	{
		parent::__construct($model);
	}

	public function recentPaginated (int $chunk = 15) : \Illuminate\Contracts\Pagination\LengthAwarePaginator
	{
		return $this->model->newQuery()->latest()->paginate($chunk);
	}
}