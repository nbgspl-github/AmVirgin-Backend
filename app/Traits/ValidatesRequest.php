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

	public function requestValid(Request $request, array $rules, array $additional = [], array $messages = []){
		// Check if there are any injected rules, if so inject them into main array.
		if (count($additional) > 0)
			foreach ($rules as $key => $value)
				if (isset($additional[$key])) {
					$extra = $additional[$key];
					if (count($extra) > 1)
						foreach ($extra as $x)
							$rules[$key][] = $x;
					else
						$rules[$key][] = $extra[0];
				}

		if ($request == null)
			$validator = Validator::make(request()->all(), $rules, $messages);
		else
			$validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) throw new ValidationException($validator->errors()->first(), $validator);
		else return $validator->validated();
	}

	public function arrayValid(array $payload = [], array $rules = [], array $additional = [], array $messages = []){
		// Check if there are any injected rules, if so inject them into main array.
		if (count($additional) > 0)
			foreach ($rules as $key => $value)
				if (isset($additional[$key])) {
					$extra = $additional[$key];
					if (count($extra) > 1)
						foreach ($extra as $x)
							$rules[$key][] = $x;
					else
						$rules[$key][] = $extra[0];
				}

		$validator = Validator::make($payload, $rules, $messages);
		if ($validator->fails()) throw new ValidationException($validator->errors()->first(), $validator);
		else return $validator->validated();
	}
}