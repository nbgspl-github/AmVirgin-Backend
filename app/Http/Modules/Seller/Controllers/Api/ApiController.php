<?php

namespace App\Http\Modules\Seller\Controllers\Api;

class ApiController extends \App\Http\Modules\Shared\Controllers\Api\ApiController
{
	protected function user () : ?\App\Models\Auth\Seller
	{
		return $this->guard()->user();
	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}