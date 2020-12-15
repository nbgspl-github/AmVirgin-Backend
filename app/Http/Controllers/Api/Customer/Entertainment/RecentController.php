<?php

namespace App\Http\Controllers\Api\Customer\Entertainment;

use App\Http\Controllers\Api\ApiController;
use App\Models\CustomerRecent;

class RecentController extends ApiController
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