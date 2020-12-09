<?php

namespace App\Http\Controllers\App\Customer\Entertainment;

use App\Http\Controllers\AppController;
use App\Models\CustomerRecent;

class RecentController extends AppController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index ()
	{
		$recent = CustomerRecent::where([
			['customerId', $this->guard()->id()],
		])->get();

	}

	protected function guard ()
	{
		return auth('customer-api');
	}
}