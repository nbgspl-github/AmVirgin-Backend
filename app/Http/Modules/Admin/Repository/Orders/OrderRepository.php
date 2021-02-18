<?php

namespace App\Http\Modules\Admin\Repository\Orders;

class OrderRepository extends \App\Http\Modules\Shared\Repository\BaseRepository implements Contracts\OrderRepository
{
	public function __construct (\App\Models\Order\Order $model)
	{
		parent::__construct($model);
	}

	public function getWithAllOrdersPaginated () : \Illuminate\Contracts\Pagination\LengthAwarePaginator
	{
		return $this->model->newQuery()->paginate();
	}
}