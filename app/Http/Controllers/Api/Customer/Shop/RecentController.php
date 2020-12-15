<?php

namespace App\Http\Controllers\Api\Customer\Shop;

use App\Http\Controllers\Api\ApiController;

class RecentController extends ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index ()
	{

	}

	protected function guard ()
	{
		return auth('customer-api');
	}
}