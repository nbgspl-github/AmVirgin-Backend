<?php

namespace App\Http\Controllers\App\Customer\Cart;

use App\Classes\Cart\CartItem;
use App\Exceptions\CartItemNotFoundException;
use App\Exceptions\MaxAllowedQuantityReachedException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Interfaces\Tables;
use App\Models\Cart;
use App\Models\CustomerWishlist;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\ConditionallyLoadsAttributes;
use Illuminate\Validation\Rule;
use Throwable;

class QuoteController extends ExtendedResourceController {
	use ValidatesRequest;
	use ConditionallyLoadsAttributes;

	protected array $rules;

	public function __construct() {
		parent::__construct();
		$this->rules = [
			'add' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
				'key' => ['bail', 'required', Rule::exists(Tables::Products, 'id')],
				'attributes' => ['bail', 'required'],
			],
			'remove' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
				'key' => ['bail', 'required', Rule::exists(Tables::Products, 'id')],
			],
			'retrieve' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
			],
			'update' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
				'key' => ['bail', 'required', Rule::exists(Tables::Products, 'id')],
				'quantity' => ['bail', 'required', 'numeric', 'min:1', 'max:10'],
			],
			'destroy' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
				'key' => ['bail', 'required', Rule::exists(Tables::Products, 'id')],
			],
			'moveToWishlist' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
			],
			'moveToCart' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
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
			]);
			$cart = Cart::retrieve($validated->sessionId);
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
		$cart = null;
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['update']);
			$cart = Cart::retrieveThrows($validated->sessionId);
			$cartItem = new CartItem($cart, $validated->key);
			$cartItem->setQuantity($validated->quantity);
			$cart->updateItem($cartItem);
			$cart->save();
			$response->status(HttpOkay)->message('Item added to cart successfully.')->setValue('data', $cart->render());
		}
		catch (MaxAllowedQuantityReachedException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getMessage())->setValue('data', $cart->render());
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('No cart found for that session');
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

	public function destroy() {
		$response = responseApp();
		$validated = null;
		$cart = null;
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['destroy']);
			$cart = Cart::retrieveThrows($validated->sessionId);
			$cartItem = new CartItem($cart, $validated->key);
			$cart->destroyItem($cartItem);
			$cart->save();
			$response->status(HttpOkay)->message('Item destroyed from cart successfully.')->setValue('data', $cart->render());
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

	public function moveToWishlist($productId) {
		$response = responseApp();
		$validated = null;
		$cart = null;
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['moveToWishlist']);
			$wishlistItem = CustomerWishlist::where([
				['customerId', $this->guard()->id()],
				['productId', $productId],
			])->first();
			if ($wishlistItem == null) {
				try {
					$cart = Cart::retrieveThrows($validated->sessionId);
					$cartItem = new CartItem($cart, $productId);
					if ($cart->contains($cartItem)) {
						CustomerWishlist::create([
							'customerId' => $this->guard()->id(),
							'productId' => $productId,
						]);
						$cart->destroyItem($cartItem);
						$cart->save();
						$response->status(HttpOkay)->message('Item moved to wishlist.');
					}
					else {
						$response->status(HttpResourceNotFound)->message('Cart does not contain the item you specified.');
					}
				}
				catch (ModelNotFoundException $exception) {
					$response->status(HttpOkay)->message('No cart was found for that session.');
				}
			}
			else {
				$response->status(HttpResourceAlreadyExists)->message('Item already exists in wishlist.');
			}
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