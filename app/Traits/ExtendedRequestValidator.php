<?php

namespace App\Traits;

use App\Exceptions\ValidationException;
use Illuminate\Support\Facades\Validator;

trait ExtendedRequestValidator {
	protected array $specialAttributes = [
		'email' => [
			'unique',
		],
		'mobile' => [
			'unique',
		],
	];

	public function validate(array $rules, array $additional = []) {
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

		$validator = Validator::make(request()->all(), $rules);
		if ($validator->fails()) throw new ValidationException($validator->errors()->first(), $validator);
		else return $validator->validated();
	}
}