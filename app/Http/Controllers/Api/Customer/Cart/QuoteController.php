<?php

namespace App\Http\Controllers\Api\Customer\Cart;

use App\Classes\Cart\CartItem;
use App\Classes\Singletons\RazorpayClient;
use App\Exceptions\CartAlreadySubmittedException;
use App\Exceptions\CartItemNotFoundException;
use App\Exceptions\MaxAllowedQuantityReachedException;
use App\Exceptions\OutOfStockException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\ApiController;
use App\Library\Enums\Common\Tables;
use App\Library\Enums\Orders\Payments\Methods;
use App\Library\Enums\Transactions\Status;
use App\Library\Utils\Extensions\Rule;
use App\Models\Cart;
use App\Models\CustomerWishlist;
use App\Models\Order;
use App\Models\Transaction;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\ConditionallyLoadsAttributes;
use Razorpay\Api\Api;
use Throwable;

class QuoteController extends ApiController
{
	use ValidatesRequest;
	use ConditionallyLoadsAttributes;

	protected array $rules;

	protected Api $client;

	public function __construct ()
	{
		parent::__construct();
		$this->client = RazorpayClient::make();
		$this->rules = [
			'add' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
				'key' => ['bail', 'required', Rule::existsPrimary(Tables::Products)->whereNull('deleted_at')],
			],
			'remove' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
				'key' => ['bail', 'required', Rule::existsPrimary(Tables::Products)->whereNull('deleted_at')],
			],
			'retrieve' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
			],
			'update' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
				'key' => ['bail', 'required', Rule::existsPrimary(Tables::Products)->whereNull('deleted_at')],
				'quantity' => ['bail', 'required', 'numeric', 'min:1', 'max:100'],
			],
			'destroy' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
				'key' => ['bail', 'required', Rule::existsPrimary(Tables::Products)->whereNull('deleted_at')],
			],
			'moveToWishlist' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
			],
			'moveToCart' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
			],
			'checkout' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
				'paymentMode' => ['bail', 'required', Rule::in(Methods::getValues())],
			],
			'submit' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
				'addressId' => ['bail', 'required', Rule::exists(Tables::ShippingAddresses, 'id')],
				'billingAddressId' => ['bail', 'sometimes', Rule::exists(Tables::ShippingAddresses, 'id')],
				'paymentMode' => ['bail', 'required', Rule::in(Methods::getValues())],
				'paymentId' => ['bail', 'required_unless:paymentMode,cash-on-delivery', 'string', 'min:16', 'max:50'],
				'orderId' => ['bail', 'required', 'string', 'min:16', 'max:50'],
				'signature' => ['bail', 'required_unless:paymentMode,cash-on-delivery', 'string', 'max:128'],
			],
			'verify' => [
				'paymentId' => ['bail', 'required', 'string', 'min:16', 'max:50'],
				'orderId' => ['bail', 'required', 'string', 'min:16', 'max:50'],
				'signature' => ['bail', 'required', 'string', 'max:128']
			]
		];
	}

	public function add (): \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		$validated = null;
		$cart = null;
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['add']);
			$cart = Cart::retrieveThrows($validated->sessionId);
			$cartItem = new CartItem($cart, $validated->key);
			$cart->addItem($cartItem);
			$cart->save();
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Item added to cart successfully.')->setValue('data', $cart->render());
		} catch (CartAlreadySubmittedException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_FORBIDDEN)->message($exception->getMessage())->setValue('data');
		} catch (OutOfStockException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage())->setValue('data', $cart->render());
		} catch (MaxAllowedQuantityReachedException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage())->setValue('data', $cart->render());
		} catch (ModelNotFoundException $exception) {
			Cart::query()->create([
				'sessionId' => $validated->sessionId,
				'status' => Status::Pending,
			]);
			$cart = Cart::retrieve($validated->sessionId);
			$cartItem = new CartItem($cart, $validated->key);
			$cart->addItem($cartItem);
			$cart->save();
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Cart initialized and item added to cart successfully.')->setValue('data', $cart->render());
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function retrieve (): \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		$validated = null;
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['retrieve']);
			$cart = Cart::retrieveThrows($validated->sessionId);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Cart retrieved successfully.')->setValue('data', $cart->render());
		} catch (CartAlreadySubmittedException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_FORBIDDEN)->message($exception->getMessage())->setValue('data');
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('No cart was found for that session.');
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function update (): \Illuminate\Http\JsonResponse
	{
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
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Item added to cart successfully.')->setValue('data', $cart->render());
		} catch (CartAlreadySubmittedException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_FORBIDDEN)->message($exception->getMessage())->setValue('data');
		} catch (OutOfStockException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage())->setValue('data', $cart->render());
		} catch (MaxAllowedQuantityReachedException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage())->setValue('data', $cart->render());
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('No cart found for that session');
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function remove (): \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		$validated = null;
		$cart = null;
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['remove']);
			$cart = Cart::retrieveThrows($validated->sessionId);
			$cartItem = new CartItem($cart, $validated->key);
			$cart->removeItem($cartItem);
			$cart->save();
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Item removed from cart successfully.')->setValue('data', $cart->render());
		} catch (CartAlreadySubmittedException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_FORBIDDEN)->message($exception->getMessage())->setValue('data');
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('No cart was found for that session.')->setValue('data', $cart->render());
		} catch (CartItemNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message($exception->getMessage())->setValue('data', $cart->render());
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function destroy (): \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		$validated = null;
		$cart = null;
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['destroy']);
			$cart = Cart::retrieveThrows($validated->sessionId);
			$cartItem = new CartItem($cart, $validated->key);
			$cart->destroyItem($cartItem);
			$cart->save();
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Item destroyed from cart successfully.')->setValue('data', $cart->render());
		} catch (CartAlreadySubmittedException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_FORBIDDEN)->message($exception->getMessage())->setValue('data');
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('No cart was found for that session.')->setValue('data', $cart->render());
		} catch (CartItemNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message($exception->getMessage())->setValue('data', $cart->render());
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function moveToWishlist ($productId): \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		$validated = null;
		$cart = null;
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['moveToWishlist']);
			$wishlistItem = CustomerWishlist::query()->where([
				['customerId', $this->guard()->id()],
				['productId', $productId],
			])->first();
			if ($wishlistItem == null) {
				try {
					$cart = Cart::retrieveThrows($validated->sessionId);
					$cartItem = new CartItem($cart, $productId);
					if ($cart->contains($cartItem)) {
						CustomerWishlist::query()->create([
							'customerId' => $this->guard()->id(),
							'productId' => $productId,
						]);
						$cart->destroyItem($cartItem);
						$cart->save();
						$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Item moved to wishlist.');
					} else {
						$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Cart does not contain the item you specified.');
					}
				} catch (ModelNotFoundException $exception) {
					$response->status(\Illuminate\Http\Response::HTTP_OK)->message('No cart was found for that session.');
				} catch (CartAlreadySubmittedException $exception) {
					$response->status(\Illuminate\Http\Response::HTTP_FORBIDDEN)->message($exception->getMessage());
				}
			} else {
				$response->status(\Illuminate\Http\Response::HTTP_CONFLICT)->message('Item already exists in wishlist.');
			}
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function checkout (): \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		$validated = null;
		$cart = null;
		try {
			/**
			 * @var $order Order
			 */
			$validated = (object)$this->requestValid(request(), $this->rules['checkout']);
			$cart = Cart::retrieveThrows($validated->sessionId);
			$transaction = $this->createNewTransaction($cart);
			$response->status(\Illuminate\Http\Response::HTTP_OK)
				->message('We\'ve prepared your order for checkout.')
				->payload(['rzpOrderId' => $transaction->rzpOrderId]);
		} catch (CartAlreadySubmittedException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_FORBIDDEN)->message($exception->getMessage());
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('No cart was found for that session.');
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function submit (): \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		$validated = null;
		$cart = null;
		try {
			/**
			 * @var $order Order
			 * @var $transaction Transaction
			 */
			$validated = (object)$this->requestValid(request(), $this->rules['submit']);
			$cart = Cart::retrieveThrows($validated->sessionId);
			$cart->customerId = $this->guard()->id();
			$cart->addressId = $validated->addressId;
			$cart->billingAddressId = $validated->billingAddressId ?? $validated->addressId;
			$cart->paymentMode = $validated->paymentMode;
			$transaction = $cart->transaction()->firstOrFail();
			$order = $cart->submit($transaction);
			$verified = $this->verify($order, $transaction);
			if ($verified) {
				$order->update([
					'status' => \App\Library\Enums\Orders\Status::Placed
				]);
				$order->subOrders()->update([
					'status' => \App\Library\Enums\Orders\Status::Placed
				]);
				$transaction->update([
					'paymentId' => $transaction->paymentId,
					'signature' => $transaction->signature,
					'verified' => true,
					'status' => Status::Paid
				]);
				$response->status(\Illuminate\Http\Response::HTTP_CREATED)
					->message('Your order was placed successfully!')
					->payload(['pending' => false, 'orderId' => $order->id])
					->setValue('orderNumber', $order->orderNumber);
			} else {
				$response->status(\Illuminate\Http\Response::HTTP_OK)
					->message('We could not verify the payment status at this time. Please allow up to 30 minutes before trying again.')
					->payload(['pending' => true, 'orderId' => null])
					->setValue('orderNumber');
			}
		} catch (CartAlreadySubmittedException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_FORBIDDEN)->message($exception->getMessage());
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message($exception->getMessage());
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function verify (Order $order, Transaction $transaction): bool
	{
		if ($transaction->isComplete()) {
			return true;
		} else {
			$transaction->update([
				'attempts' => $transaction->attempts + 1
			]);
			if ($order->paymentMode == Methods::CashOnDelivery) {
				return true;
			} else {
				return $this->client->utility->verifyPaymentSignature([
					'razorpay_signature' => $transaction->signature,
					'razorpay_payment_id' => $transaction->paymentId,
					'order_id' => $transaction->rzpOrderId
				]);
			}
		}
	}

	protected function createNewTransaction (Cart $cart)
	{
		$rzpTransaction = (object)$this->client->order->create([
			'amount' => $this->toAtomicAmount($cart->total),
			'currency' => 'INR'
		]);
		$transaction = Transaction::query()->create([
			'rzpOrderId' => $rzpTransaction->id,
			'amountRequested' => $this->fromAtomicAmount($rzpTransaction->amount),
			'amountReceived' => $this->fromAtomicAmount($rzpTransaction->amount_paid),
			'currency' => $rzpTransaction->currency,
			'status' => $rzpTransaction->status,
			'attempts' => $rzpTransaction->attempts,
		]);
		$cart->transaction()->associate($transaction)->save();
		return $transaction;
	}

	protected function toAtomicAmount ($amount)
	{
		return $amount * 100;
	}

	protected function fromAtomicAmount ($amount): float
	{
		return $amount / 100.0;
	}

	protected function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}