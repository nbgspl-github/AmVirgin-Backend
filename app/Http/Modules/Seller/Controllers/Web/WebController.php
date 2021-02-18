<?php

namespace App\Http\Modules\Seller\Controllers\Web;

class WebController extends \App\Http\Modules\Shared\Controllers\Web\WebController
{
	protected function guard ()
	{
		return auth(self::SELLER);
	}
}
