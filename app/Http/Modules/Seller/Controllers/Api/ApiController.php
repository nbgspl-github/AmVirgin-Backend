<?php

namespace App\Http\Modules\Seller\Controllers\Api;

class ApiController extends \App\Http\Modules\Shared\Controllers\Api\ApiController
{
	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}