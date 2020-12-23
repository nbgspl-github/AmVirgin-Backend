<?php

namespace App\Http\Modules\Customer\Controllers\Api\Entertainment;

use App\Models\CustomerRecent;

class RecentController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
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
}