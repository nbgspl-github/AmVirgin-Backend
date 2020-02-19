<?php

namespace App\Http\Controllers\App\Customer\Cart;

use App\Classes\Cart\Cart;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Interfaces\Tables;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Throwable;

class QuoteController extends ExtendedResourceController {
	use ValidatesRequest;

	protected $rules = [];

	public function __construct() {
		parent::__construct();
		$this->rules = [
			'add' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
				'customerId' => ['bail', 'nullable', Rule::exists(Tables::Customers, 'id')],
				'key' => ['bail', 'required', Rule::exists(Tables::Products, 'id')],
				'quantity' => ['bail', 'nullable', 'numeric', 'min:1', 'max:10'],
			],
		];
	}

	public function add() {
		$response = responseApp();
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['add']);
			$cart = null($validated->customerId) ? new Cart($validated->sessionId) : new Cart($validated->sessionId, $validated->customerId);
		}
		catch (ModelNotFoundException $exception) {
			\App\Models\Cart::create([
				'sessionId',
			]);
			$response->status(HttpResourceNotFound)->message('No cart exists for that session. Please create a session and try again.');
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getError());
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function retrieve() {
		try {

		}
		catch (Throwable $exception) {

		}
		finally {

		}
	}

	public function update() {

	}

	public function remove() {

	}

	protected function guard() {
		return auth('customer-api');
	}
}