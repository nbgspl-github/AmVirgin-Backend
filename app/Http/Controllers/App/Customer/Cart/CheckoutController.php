<?php

namespace App\Http\Controllers\App\Customer\Cart;

use App\Classes\Singletons\RazorpayClient;
use App\Enums\Transactions\Status;
use App\Exceptions\ValidationException;
use App\Http\Controllers\AppController;
use App\Models\Order;
use App\Models\Transaction;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Razorpay\Api\Api;

class CheckoutController extends AppController
{
	use ValidatesRequest;

	protected Api $client;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AuthCustomer);
		$this->client = RazorpayClient::make();
		$this->rules = [
			'verify' => [
				'paymentId' => ['bail', 'required', 'string', 'min:16', 'max:50'],
				'orderId' => ['bail', 'required', 'string', 'min:16', 'max:50'],
				'signature' => ['bail', 'required', 'string', 'max:128']
			]
		];
	}

	public function initiate (Order $order)
	{
		$response = responseApp();
		try {
			/**
			 * @var $transaction ?Transaction
			 */
			$transaction = $order->transaction;
			if ($transaction != null) {
				if ($transaction->isComplete()) {
					$response->status(HttpResourceAlreadyExists)->message('Payment has already been done for this order.');
				} else {
					$transaction->update(['attempts' => ($transaction->attempts ?? 1) + 1]);
					$response->status(HttpOkay)->message('Order transaction already exists!')->setPayload(['rzpOrderId', $transaction->rzpOrderId]);
				}
			} else {
				$transaction = $this->createNewTransaction($order);
				$response->status(HttpOkay)->message('Order transaction has been created!')->setPayload(['rzpOrderId', $transaction->rzpOrderId]);
			}
		} catch (\Throwable $e) {
			$response->status(HttpServerError)->message($e->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function verify (Order $order)
	{
		$response = responseApp();
		try {
			$payload = (object)$this->requestValid(request(), $this->rules['verify']);
			$transaction = $order->transaction()->firstOrFail();
			if ($transaction->isComplete()) {
				$response->status(HttpResourceAlreadyExists)->message('Transaction for this order is already settled!');
			} else {
				$verified = $this->client->utility->verifyPaymentSignature([
					'razorpay_signature' => $payload->signature,
					'razorpay_payment_id' => $payload->paymentId,
					'order_id' => $transaction->rzpOrderId
				]);
				$transaction->increment('attempts');
				if ($verified) {
					$order->update([
						'status' => \App\Enums\Orders\Status::Placed
					]);
					$transaction->update([
						'paymentId' => $payload->paymentId,
						'signature' => $payload->signature,
						'verified' => true,
						'status' => Status::Paid
					]);
					$response->status(HttpCreated)->message('Payment completed successfully for this order!');
				} else {
					$response->status(HttpNoContent)->message('We could not verify the payment. Please allow up to 30 minutes before trying again.');
				}
			}
		} catch (ModelNotFoundException $e) {

		} catch (ValidationException $e) {
			$response->status(HttpInvalidRequestFormat)->message($e->getError());
		} finally {
			return $response->send();
		}
	}

	public function createNewTransaction (Order $order)
	{
		$rzpTransaction = (object)$this->client->order->create([
			'amount' => $this->toAtomicAmount($order->total),
			'currency' => 'INR'
		]);
		return $order->transaction()->create([
			'rzpOrderId' => $rzpTransaction->id,
			'amountRequested' => $this->fromAtomicAmount($rzpTransaction->amount),
			'amountReceived' => $this->fromAtomicAmount($rzpTransaction->amount_paid),
			'currency' => $rzpTransaction->currency,
			'status' => $rzpTransaction->status,
			'attempts' => $rzpTransaction->attempts,
		]);
	}

	protected function toAtomicAmount ($amount, $currency = 'INR')
	{
		return $amount * 100;
	}

	protected function fromAtomicAmount ($amount, $currency = 'INR')
	{
		return $amount / 100.0;
	}

	protected function guard ()
	{
		return auth(self::CustomerAPI);
	}
}