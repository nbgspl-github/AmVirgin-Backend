<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

trait ValidatesRequest {
	public function requestValid(Request $request, array $rules = []) {
		$validator = Validator::make($request->all(), $rules);
	}
}