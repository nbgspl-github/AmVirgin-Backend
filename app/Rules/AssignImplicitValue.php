<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class AssignImplicitValue implements Rule {
	protected $request;
	protected $implicitValue;

	/**
	 * Create a new rule instance.
	 *
	 * @param Request $request
	 */
	public function __construct(Request $request, $implicitValue = 1) {
		$this->request = $request;
		$this->implicitValue = $implicitValue;
	}

	/**
	 * Determine if the validation rule passes.
	 *
	 * @param string $attribute
	 * @param mixed $value
	 * @return bool
	 */
	public function passes($attribute, $value) {
		if (!null($value))
			$this->request->$attribute = $this->implicitValue;
		return true;
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message() {
		return 'The validation error message.';
	}
}
