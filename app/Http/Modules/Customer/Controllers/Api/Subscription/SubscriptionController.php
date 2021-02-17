<?php

namespace App\Http\Modules\Customer\Controllers\Api\Subscription;

use App\Classes\Singletons\RazorpayClient;
use App\Http\Modules\Customer\Requests\Subscription\SubmitRequest;
use App\Models\Order\Transaction;

class SubscriptionController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	protected \Razorpay\Api\Api $client;

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_CUSTOMER);
		$this->client = RazorpayClient::make();
	}

	public function checkout (\App\Models\SubscriptionPlan $plan) : \Illuminate\Http\JsonResponse
	{
		$subscription = $this->createPlaceholderSubscription($plan);
		return $this->sendSubscriptionCheckoutResponse($subscription);
	}

	/**
	 * @param \App\Models\SubscriptionPlan $plan
	 * @return \Illuminate\Database\Eloquent\Model|\App\Models\Subscription
	 */
	protected function createPlaceholderSubscription (\App\Models\SubscriptionPlan $plan) : \Illuminate\Database\Eloquent\Model
	{
		$transaction = $this->createSubscriptionTransaction($plan->offerPrice);
		return $this->customer()->subscriptions()->create([
			'subscription_plan_id' => $plan->id,
			'transaction_id' => $transaction->id
		]);
	}

	protected function createSubscriptionTransaction ($amount)
	{
		$rzpTransaction = (object)$this->client->order->create([
			'amount' => toAtomicAmount($amount),
			'currency' => 'INR'
		]);
		return Transaction::query()->create([
			'rzpOrderId' => $rzpTransaction->id,
			'amountRequested' => fromAtomicAmount($rzpTransaction->amount),
			'amountReceived' => fromAtomicAmount($rzpTransaction->amount_paid),
			'currency' => $rzpTransaction->currency,
			'status' => $rzpTransaction->status,
			'attempts' => $rzpTransaction->attempts,
		]);
	}

	protected function sendSubscriptionCheckoutResponse (\App\Models\Subscription $subscription) : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare([
			'rzpOrderId' => $subscription->transaction->rzpOrderId,
			'transactionId' => $subscription->transaction->id
		], \Illuminate\Http\Response::HTTP_CREATED, 'We\'ve prepared your checkout details.');
	}

	public function submit (SubmitRequest $request) : \Illuminate\Http\JsonResponse
	{
		$transaction = Transaction::findBy(['id' => $request->transactionId]);
		$verified = $this->verify($request->validated(), $transaction);
		if ($verified) {
			$this->customer()->activateSubscription($transaction->subscription);
			return $this->sendSubscriptionActivatedResponse($transaction->subscription);
		}
		return $this->sendPaymentVerificationFailedResponse();
	}

	protected function verify (array $parameters, Transaction $transaction) : bool
	{
		try {
			$this->client->utility->verifyPaymentSignature([
				'razorpay_signature' => $parameters['signature'],
				'razorpay_payment_id' => $parameters['paymentId'],
				'razorpay_order_id' => $transaction->rzpOrderId
			]);
			return true;
		} catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
			return false;
		}
	}

	/**
	 * @param \App\Models\Subscription $subscription
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function sendSubscriptionActivatedResponse (\App\Models\Subscription $subscription) : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare([
			'key' => $subscription->subscription_plan_id,
			'name' => $subscription->plan->name ?? \App\Library\Utils\Extensions\Str::NotAvailable,
			'duration' => [
				'actual' => $subscription->plan->duration,
				'remaining' => $subscription->valid_from->diffInDays($subscription->valid_until),
				'expires' => $subscription->valid_until
			]
		], \Illuminate\Http\Response::HTTP_CREATED, 'Your subscription is active now.');
	}

	protected function sendPaymentVerificationFailedResponse () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			[], \Illuminate\Http\Response::HTTP_OK, 'Your payment could not be verified. Please try again in a while.'
		);
	}
}