<?php

namespace App\Http\Controllers\App\Customer\Orders;

use App\Http\Controllers\AppController;
use App\Models\Order;
use App\Models\OrderItem;

class StatusController extends AppController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function return (OrderItem $order)
	{

	}

	public function cancel (OrderItem $order)
	{

	}

	public function reschedule (OrderItem $order)
	{

	}

	public function track (Order $order)
	{

	}

	protected function guard ()
	{
		return \auth(self::CustomerAPI);
	}
}