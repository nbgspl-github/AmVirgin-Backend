<?php
/**
 * Copyright (c) 2019. Aviral Singh
 * Validator implementation with Promises giving callbacks.
 */

namespace App\Classes\Builders;

use App\Contracts\FluentConstructor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Implementation of a Validator using Laravel's inbuilt validator with callbacks for appropriate events.
 * @package App\Classes\Builders
 */
class ValidatorPromise implements FluentConstructor {

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var callable
	 */
	private $callableSuccess;

	/**
	 * @var callable
	 */
	private $callableFailure;

	/**
	 * @var callable
	 */
	private $callableRuleset;

	/**
	 * @var callable
	 */
	private $customEvaluator;

	/**
	 * @var array
	 */
	private $rules;

	/**
	 * @var \Illuminate\Contracts\Validation\Validator
	 */
	private $validator;

	/**
	 * ValidatorPromise constructor.
	 */
	private function __construct() {
	}

	/**
	 * Sets the Request instance to validate.
	 * @param Request $request
	 * @return $this
	 */
	public function setRequest(Request $request) {
		$this->request = $request;
		return $this;
	}

	/**
	 * Sets the rules for validation.
	 * @param array $rules
	 * @return $this
	 */
	public function setRuleset(array $rules = []) {
		$this->rules = $rules;
		return $this;
	}

	/**
	 * Sets a callable which returns a rule set to be used for validation.
	 * Use this when you need to provide a rule set based on a initial decision.
	 * @param callable $callableRuleset
	 * @return ValidatorPromise
	 */
	public function setRulesetCallable(callable $callableRuleset) {
		$this->callableRuleset = $callableRuleset;
		return $this;
	}

	/**
	 * Sets a function which determines whether the validation has passed or failed.
	 * @param callable $customEvaluator
	 * @return $this
	 */
	public function setCustomEvaluator(callable $customEvaluator) {
		$this->customEvaluator = $customEvaluator;
		return $this;
	}

	/**
	 * Sets a callback to be invoked when validation succeeds.
	 * @param callable $callable
	 * @return $this
	 */
	public function onSuccess(callable $callable) {
		$this->callableSuccess = $callable;
		return $this;
	}

	/**
	 * Sets a callback to be called when validation fails.
	 * @param callable $callable
	 * @return $this
	 */
	public function onFailure(callable $callable) {
		$this->callableFailure = $callable;
		return $this;
	}

	/**
	 * Runs validation on given request using provided ruleset.
	 * @return JsonResponse
	 */
	public function validate() {
		$response = null;
		if ($this->customEvaluator != null) {
			$result = call_user_func($this->customEvaluator, $this->request);
			if ($result)
				$response = call_user_func($this->callableSuccess, $this->request);
			else
				$response = call_user_func($this->callableFailure, $this->request);
		} else {
			if ($this->callableRuleset != null) {
				$this->rules = call_user_func($this->callableRuleset, $this->request);
			}
			$this->validator = Validator::make($this->request->all(), $this->rules);
			$response = null;
			if (!$this->validator->fails())
				$response = call_user_func($this->callableSuccess, $this->request);
			else
				$response = call_user_func($this->callableFailure, $this->validator);
		}
		return $response;
	}

	/**
	 *  Makes a new instance and returns it.
	 * @return ValidatorPromise
	 */
	public static function instance() {
		return new self();
	}
}