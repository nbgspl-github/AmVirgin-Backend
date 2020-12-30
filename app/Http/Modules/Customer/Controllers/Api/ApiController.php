<?php

namespace App\Http\Modules\Customer\Controllers\Api;

class ApiController extends \App\Http\Modules\Shared\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_CUSTOMER);
	}

	protected final function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}