<?php

namespace App\Traits;

use App\Library\Http\Response\AppResponse;

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

	public function responseApp () : AppResponse
	{
		return AppResponse::instance();
	}
}