<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Validation\Validator;

class ValidationException extends Exception {
	/**
	 * @var string
	 */
	private $error;

	/**
	 * @var Validator
	 */
	private $validator;

	/**
	 * ValidationException constructor.
	 * @param string $error
	 * @param Validator $validator
	 */
	public function __construct(string $error, Validator $validator) {
		parent::__construct($error);
		$this->error = $error;
		$this->validator = $validator;
	}

	/**
	 * @return string
	 */
	public function getError(): string {
		return $this->error;
	}

	/**
	 * @return Validator
	 */
	public function getValidator(): Validator {
		return $this->validator;
	}
}
