<?php

namespace App\Http\Controllers\App\Customer\Cart;

use App\Classes\Cart\CartItem;
use App\Exceptions\CartItemNotFoundException;
use App\Exceptions\MaxAllowedQuantityReachedException;
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
				'attributes' => ['bail', 'required'],
			],
			'remove' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
				'customerId' => ['bail', 'nullable', Rule::exists(Tables::Customers, 'id')],
				'key' => ['bail', 'required', Rule::exists(Tables::Products, 'id')],
			],
			'retrieve' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
				'customerId' => ['bail', 'nullable', Rule::exists(Tables::Customers, 'id')],
			],
			'update' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
				'customerId' => ['bail', 'nullable', Rule::exists(Tables::Customers, 'id')],
				'key' => ['bail', 'required', Rule::exists(Tables::Products, 'id')],
			],
		];
	}

	public function add() {
		$response = responseApp();
		$validated = null;
		$cart = null;
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['add']);
			$cart = Cart::retrieveThrows($validated->sessionId);
			$cart->customerId = $validated->customerId;
			$cartItem = new CartItem($cart, $validated->key, $validated->attributes);
			$cart->addItem($cartItem);
			$cart->save();
			$response->status(HttpOkay)->message('Item added to cart successfully.')->setValue('data', $cart->render());
		}
		catch (MaxAllowedQuantityReachedException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getMessage())->setValue('data', $cart->render());
		}
		catch (ModelNotFoundException $exception) {
			\App\Models\Cart::create([
				'sessionId' => $validated->sessionId,
				'customerId' => $validated->customerId,
			]);
			$cart = Cart::retrieveThrows($validated->sessionId);
			$cartItem = new CartItem($cart, $validated->key, $validated->attributes);
			$cart->addItem($cartItem);
			$cart->save();
			$response->status(HttpOkay)->message('Cart initialized and item added to cart successfully.')->setValue('data', $cart->render());
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getError());
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getTraceAsString());
		}
		finally {
			return $response->send();
		}
	}

	public function retrieve() {
		$response = responseApp();
		$validated = null;
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['retrieve']);
			$cart = Cart::retrieveThrows($validated->sessionId);
			$response->status(HttpOkay)->message('Cart retrieved successfully.')->setValue('data', $cart->render());
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpOkay)->message('No cart was found for that session.');
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getError());
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getTraceAsString());
		}
		finally {
			return $response->send();
		}
	}

	public function update() {
		$response = responseApp();
		$validated = null;
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['remove']);
			$cart = Cart::retrieveThrows($validated->sessionId);
			$response->status(HttpOkay)->message('Cart retrieved successfully.')->setValue('data', $cart->render());
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpOkay)->message('No cart was found for that session.');
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getError());
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getTraceAsString());
		}
		finally {
			return $response->send();
		}
	}

	public function remove() {
		$response = responseApp();
		$validated = null;
		$cart = null;
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['remove']);
			$cart = Cart::retrieveThrows($validated->sessionId);
			$cart->customerId = $validated->customerId;
			$cartItem = new CartItem($cart, $validated->key);
			$cart->removeItem($cartItem);
			$cart->save();
			$response->status(HttpOkay)->message('Item removed from cart successfully.')->setValue('data', $cart->render());
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpOkay)->message('No cart was found for that session.')->setValue('data', $cart->render());
		}
		catch (CartItemNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message($exception->getMessage())->setValue('data', $cart->render());
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getError());
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getTraceAsString());
		}
		finally {
			return $response->send();
		}
	}

	protected function guard() {
		return auth('customer-api');
	}
}