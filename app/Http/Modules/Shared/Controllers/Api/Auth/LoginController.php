<?php

namespace App\Http\Modules\Shared\Controllers\Api\Auth;

class LoginController extends \App\Http\Modules\Shared\Controllers\Api\AuthController
{
	public function __construct (\App\Library\Database\Eloquent\Model $model)
	{
		parent::__construct($model);
	}

	public function login ()
	{

	}
}