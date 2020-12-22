<?php

namespace App\Http\Controllers\API\Customer\Orders;

use App\Http\Controllers\AppController;
use App\Models\Order\Order;
use App\Models\Order\Item;

class StatusController extends AppController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function return (Item $order)
	{

	}

	public function cancel (Item $order)
	{

	}

	public function reschedule (Item $order)
	{

	}

	public function track (Order $order)
	{

	}

	protected function guard ()
	{
		return \auth(self::CUSTOMER_API);
	}
}