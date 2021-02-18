<?php

namespace App\Http\Modules\Seller\Controllers\Api;

use App\Models\Auth\Seller;

class ApiController extends \App\Http\Modules\Shared\Controllers\Api\ApiController
{
	/**
	 * Gets the seller who issued this request.
	 * @return ?Seller
	 */
	protected function seller () : ?\App\Models\Auth\Seller
	{
		return $this->guard()->user();
	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}