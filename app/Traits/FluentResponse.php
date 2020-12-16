<?php

namespace App\Traits;

use App\Classes\Builders\ResponseBuilder;

trait FluentResponse
{
	private function success () : ResponseBuilder
	{
		return ResponseBuilder::asSuccess();
	}

	private function failed () : ResponseBuilder
	{
		return ResponseBuilder::asFailure();
	}

	private function error () : ResponseBuilder
	{
		return ResponseBuilder::asError();
	}

	public function responseApp () : ResponseBuilder
	{
		return ResponseBuilder::instance();
	}
}