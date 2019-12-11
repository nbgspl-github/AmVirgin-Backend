<?php

namespace App\Traits;

use App\Exceptions\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait ValidatesRequest{
	protected $specialAttributes = [
		'email' => [
			'unique',
		],
		'mobile' => [
			'unique',
		],
	];

	public function requestValid(Request $request, array $rules = []){
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			throw new ValidationException($validator->errors()->first(), $validator);
		}
		else {
			return true;
		}
	}
}