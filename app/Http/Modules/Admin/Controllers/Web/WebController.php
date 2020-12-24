<?php

namespace App\Http\Modules\Admin\Controllers\Web;

class WebController extends \App\Http\Modules\Shared\Controllers\Web\WebController
{
	protected function guard ()
	{
		return auth(self::ADMIN);
	}
}