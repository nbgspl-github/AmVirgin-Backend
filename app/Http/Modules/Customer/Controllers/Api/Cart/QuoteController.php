<?php

namespace App\Http\Modules\Customer\Controllers\Api\Cart;

use App\Classes\Cart\CartItem;
use App\Classes\Singletons\RazorpayClient;
use App\Exceptions\CartAlreadySubmittedException;
use App\Exceptions\CartItemNotFoundException;
use App\Exceptions\MaxAllowedQuantityReachedException;
use App\Exceptions\OutOfStockException;
use App\Library\Enums\Orders\Payments\Methods;
use App\Library\Enums\Transactions\Status;
use App\Models\Cart\Cart;
use App\Models\CustomerWishlist;
use App\Models\Order\Order;
use App\Models\Order\Transaction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Razorpay\Api\Api;

class QuoteController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	protected Api $client;

	public function __construct ()
	{
		parent::__construct();
		$this->client = RazorpayClient::make();
	}

	public function add (\App\Http\Modules\Customer\Requests\Cart\AddRequest $request) : \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		$validated = null;
		$cart = null;
		try {
			$validated = (object)$request->validated();
			$cart = Cart::retrieveThrows($validated->sessionId);
			$cartItem = new CartItem($cart, $validated->key);
			$cart->addItem($cartItem);
			$cart->save();
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Item added to cart successfully.')->setValue('data', $cart->render());
		} catch (CartAlreadySubmittedException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_FORBIDDEN)->message($exception->getMessage())->setValue('data');
		} catch (OutOfStockException | MaxAllowedQuantityReachedException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage())->setValue('data', $cart->render());
		} catch (ModelNotFoundException $exception) {
			/**
			 * @var Cart $cart
			 */
			Cart::query()->create([
				'sessionId' => $validated->sessionId,
				'status' => \App\Library\Enums\Cart\Status::Pending,
			]);
			$cart = Cart::retrieve($validated->sessionId);
			$cartItem = new CartItem($cart, $validated->key);
			$cart->addItem($cartItem);
			$cart->save();
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Cart initialized and item added to cart successfully.')->setValue('data', $cart->render());
		}
		return $response->send();
	}

	public function retrieve (\App\Http\Modules\Customer\Requests\Cart\RetrieveRequest $request) : \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		$validated = null;
		try {
			$validated = (object)$request->validated();
			$cart = Cart::retrieveThrows($validated->sessionId);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Cart retrieved successfully.')->setValue('data', $cart->render());
		} catch (CartAlreadySubmittedException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_FORBIDDEN)->message($exception->getMessage())->setValue('data');
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('No cart was found for that session.');
		}
		return $response->send();
	}

	public function update (\App\Http\Modules\Customer\Requests\Cart\UpdateRequest $request) : \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		$validated = null;
		$cart = null;
		try {
			$validated = (object)$request->validated();
			$cart = Cart::retrieveThrows($validated->sessionId);
			$cartItem = new CartItem($cart, $validated->key);
			$cartItem->setQuantity($validated->quantity);
			$cart->updateItem($cartItem);
			$cart->save();
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Item added to cart successfully.')->setValue('data', $cart->render());
		} catch (CartAlreadySubmittedException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_FORBIDDEN)->message($exception->getMessage())->setValue('data');
		} catch (OutOfStockException | MaxAllowedQuantityReachedException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage())->setValue('data', $cart->render());
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('No cart found for that session');
		}
		return $response->send();
	}

	public function remove (\App\Http\Modules\Customer\Requests\Cart\RemoveRequest $request) : \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		$validated = null;
		$cart = null;
		try {
			$validated = (object)$request->validated();
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
		}
		return $response->send();
	}

	public function destroy (\App\Http\Modules\Customer\Requests\Cart\DestroyRequest $request) : \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		$validated = null;
		$cart = null;
		try {
			$validated = (object)$request->validated();
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
		}
		return $response->send();
	}

	public function moveToWishlist (\App\Http\Modules\Customer\Requests\Cart\MoveToWishlistRequest $request, $productId) : \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		$validated = null;
		$cart = null;
		$validated = (object)$request->validated();
		$wishlistItem = CustomerWishlist::query()->where([
			['customerId', $this->customer()->id],
			['productId', $productId],
		])->first();
		if ($wishlistItem == null) {
			try {
				$cart = Cart::retrieveThrows($validated->sessionId);
				$cartItem = new CartItem($cart, $productId);
				if ($cart->contains($cartItem)) {
					CustomerWishlist::query()->create([
						'customerId' => $this->customer()->id,
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
		return $response->send();
	}

	public function checkout (\App\Http\Modules\Customer\Requests\Cart\CheckoutRequest $request) : \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		$validated = null;
		$cart = null;
		try {
			/**
			 * @var $order Order
			 */
			$validated = (object)$request->validated();
			$cart = Cart::retrieveThrows($validated->sessionId);
			$transaction = $this->createNewTransaction($cart);
			$response->status(\Illuminate\Http\Response::HTTP_OK)
				->message('We\'ve prepared your order for checkout.')
				->payload(['rzpOrderId' => $transaction->rzpOrderId]);
		} catch (CartAlreadySubmittedException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_FORBIDDEN)->message($exception->getMessage());
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('No cart was found for that session.');
		}
		return $response->send();
	}

	public function submit (\App\Http\Modules\Customer\Requests\Cart\SubmitRequest $request) : \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		$validated = null;
		$cart = null;
		try {
			/**
			 * @var $order Order
			 * @var $transaction Transaction
			 */
			$validated = (object)$request->validated();
			$cart = Cart::retrieveThrows($validated->sessionId);
			$cart->customerId = $this->customer()->id;
			$cart->addressId = $validated->addressId;
			$cart->billingAddressId = $validated->billingAddressId ?? $validated->addressId;
			$cart->paymentMode = $validated->paymentMode;
			$transaction = $cart->transaction()->firstOrFail();
			$transaction = $transaction->refresh();
			$order = $cart->submit($transaction);
			$verified = $this->verify($order, $transaction, $validated);
			if ($verified) {
				$cart->update([
					'status' => \App\Library\Enums\Cart\Status::Submitted
				]);
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
		}
		return $response->send();
	}

	protected function verify (Order $order, Transaction $transaction, $validated) : bool
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
				try {
					$this->client->utility->verifyPaymentSignature([
						'razorpay_signature' => $validated->signature,
						'razorpay_payment_id' => $validated->paymentId,
						'razorpay_order_id' => $transaction->rzpOrderId
					]);
					return true;
				} catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
					return false;
				}
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

	protected function fromAtomicAmount ($amount) : float
	{
		return $amount / 100.0;
	}
}