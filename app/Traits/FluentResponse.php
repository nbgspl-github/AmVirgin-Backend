<?php

namespace App\Traits;

use App\Library\Http\AppResponse;

trait FluentResponse
{
	private function success () : AppResponse
	{
		return AppResponse::asSuccess();
	}

	private function failed () : AppResponse
	{
		return AppResponse::asFailure();
	}

	private function error () : AppResponse
	{
		return AppResponse::asError();
	}
}