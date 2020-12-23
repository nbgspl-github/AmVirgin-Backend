<?php

namespace App\Http\Modules\Customer\Controllers\Api;

class ApiController extends \App\Http\Controllers\Api\ApiController
{
	protected final function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}