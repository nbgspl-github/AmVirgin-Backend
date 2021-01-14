<?php

namespace App\Http\Modules\Admin\Controllers\Web\Orders;

class OrderController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	/**
	 * @var \App\Models\Order\Order
	 */
	protected $model;

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
		$this->model = app(\App\Models\Order\Order::class);
	}

	public function index ()
	{
		return view('admin.orders.index')->with('orders',
			$this->paginateWithQuery(
				$this->model->newQuery()->latest()->whereLike('orderNumber', $this->queryParameter()))
		);
	}

	public function show (\App\Models\Order\Order $order)
	{
		return view('admin.orders.show')->with('order', $order);
	}
}