<?php

namespace App\Http\Modules\Admin\Controllers\Web\Orders;

use App\Http\Modules\Admin\Repository\Orders\Contracts\OrderRepository;

class OrderController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	/**
	 * @var OrderRepository
	 */
	protected $repository;

	public function __construct (OrderRepository $repository)
	{
		parent::__construct();
		$this->repository = $repository;
	}

	public function index ()
	{
		return view('admin.orders.index')->with('orders',
			$this->repository->getWithAllOrdersPaginated()
		);
	}
}