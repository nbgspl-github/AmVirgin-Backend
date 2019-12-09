<?php

namespace App\Traits;

use App\Exceptions\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait ValidatesRequest {
	public function requestValid(Request $request, array $rules = []) {
		$validator = Validator::make($request->all(), $rules);
		$rulesA = [
			'email' => ['bail', 'required', 'email', 'exists:sellers,email'],
			'password' => ['bail', 'required', 'string', 'min:4', 'max:64'],
		];
		if ($validator->fails()) {
			throw new ValidationException($validator->errors()->first(), $validator);
		}
		else {
			return true;
		}
	}
}