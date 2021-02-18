<?php

namespace App\Http\Modules\Customer\Controllers\Api;

use App\Models\Auth\Customer;

class ApiController extends \App\Http\Modules\Shared\Controllers\Api\ApiController
{
	/**
	 * Gets the customer who issued this request.
	 * @return ?Customer
	 */
	protected function customer () : ?Customer
	{
		return $this->guard()->user();
	}

	protected final function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}