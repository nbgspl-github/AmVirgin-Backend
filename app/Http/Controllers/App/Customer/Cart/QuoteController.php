<?php

namespace App\Http\Controllers\App\Customer\Cart;

use App\Classes\Cart\CartItem;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Interfaces\Tables;
use App\Models\Cart;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\ConditionallyLoadsAttributes;
use Illuminate\Validation\Rule;
use Throwable;

class QuoteController extends ExtendedResourceController {
	use ValidatesRequest;
	use ConditionallyLoadsAttributes;

	protected $rules = [];

	public function __construct() {
		parent::__construct();
		$this->rules = [
			'add' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
				'customerId' => ['bail', 'nullable', Rule::exists(Tables::Customers, 'id')],
				'key' => ['bail', 'required', Rule::exists(Tables::Products, 'id')],
				'quantity' => ['bail', 'nullable', 'numeric', 'min:1', 'max:10'],
				'attributes' => ['bail', 'required'],
			],
		];
	}

	public function add() {
		$response = responseApp();
		$validated = null;
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['add']);
			$cart = null($validated->customerId) ? Cart::retrieveThrows($validated->sessionId) : Cart::retrieveThrows($validated->sessionId, $validated->customerId);
			$cartItem = new CartItem($cart, $validated->key, $validated->attributes);
			$cart->addItem($cartItem);
			$cart->addItem($cartItem);
			$response->status(HttpOkay)->message('Item added to cart successfully.')->setValue('data', $cart->render());
		}
		catch (ModelNotFoundException $exception) {
			\App\Models\Cart::create([
				'sessionId' => $validated->sessionId,
				'customerId' => $validated->customerId,
			]);
			$cart = null($validated->customerId) ? Cart::retrieveThrows($validated->sessionId) : Cart::retrieveThrows($validated->sessionId, $validated->customerId);
			$cartItem = new CartItem($cart, $validated->key, $validated->attributes);
			$cart->addItem($cartItem);
			$cart->addItem($cartItem);
			$response->status(HttpOkay)->message('Cart initialized and item added to cart successfully.')->setValue('data', $cart->render());
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getError());
		}
		catch (Throwable $exception) {
			dd($exception);
			$response->status(HttpServerError)->message($exception->getTraceAsString());
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