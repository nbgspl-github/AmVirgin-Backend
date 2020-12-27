<?php

namespace App\Http\Modules\Admin\Controllers\Web;

class WebController extends \App\Http\Modules\Shared\Controllers\Web\WebController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
	}

	protected function guard ()
	{
		return auth(self::ADMIN);
	}
}