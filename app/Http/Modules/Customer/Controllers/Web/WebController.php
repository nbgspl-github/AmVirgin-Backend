<?php

namespace App\Http\Modules\Customer\Controllers\Web;

class WebController extends \App\Http\Modules\Shared\Controllers\Web\WebController
{
	protected function guard ()
	{
		return auth(self::CUSTOMER);
	}
}