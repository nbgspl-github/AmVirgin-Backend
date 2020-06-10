<?php

namespace App\Traits;

use App\Classes\Builders\ResponseBuilder;

trait FluentResponse{
	private function success(){
		return ResponseBuilder::asSuccess();
	}

	private function failed(){
		return ResponseBuilder::asFailure();
	}

	private function error(){
		return ResponseBuilder::asError();
	}

	public function response(){
		return ResponseBuilder::instance();
	}
}