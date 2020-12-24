<?php

namespace App\Http\Modules\Customer\Controllers\Api;

class ApiController extends \App\Http\Modules\Shared\Controllers\Api\ApiController
{
	protected final function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}