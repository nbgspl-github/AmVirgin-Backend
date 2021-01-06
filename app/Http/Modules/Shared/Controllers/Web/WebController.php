<?php

namespace App\Http\Modules\Shared\Controllers\Web;

abstract class WebController extends \App\Http\Modules\Shared\Controllers\BaseController
{
	protected const ADMIN = 'admin';

	protected const CUSTOMER = 'customer-api';

	protected abstract function guard ();
}