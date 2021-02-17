<?php

namespace App\Http\Modules\Customer\Controllers\Api\Subscription\Rental;

use App\Http\Modules\Customer\Requests\Subscription\Rental\SubmitRequest;
use App\Http\Modules\Customer\Resources\Subscription\Rental\VideoResource;
use App\Models\Order\Transaction;

class RentalController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	protected \Razorpay\Api\Api $client;

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_CUSTOMER);
		$this->client = \App\Classes\Singletons\RazorpayClient::make();
	}

	public function index () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			VideoResource::collection($this->customer()->activeRentals()->get())
		);
	}

	public function checkout (\App\Models\Video\Video $video) : \Illuminate\Http\JsonResponse
	{
		if (!$this->customer()->isRented($video) || $this->customer()->isRentalExpired($video)) {
			$transaction = $this->createPlaceholderTransaction($video->price);
			return $this->sendCheckoutResponse($transaction);
		} else {
			return $this->sendRentalActiveResponse();
		}
	}

	public function submit (SubmitRequest $request, \App\Models\Video\Video $video) : \Illuminate\Http\JsonResponse
	{
		if (!$this->customer()->isRented($video) || $this->customer()->isRentalExpired($video)) {
			$transaction = $this->createTransaction($request);
			$this->customer()->addRentalVideo($video, $transaction);
			return $this->sendRentalActivatedResponse();
		} else {
			return $this->sendRentalActiveResponse();
		}
	}

	/**
	 * @param SubmitRequest $request
	 * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|\App\Models\Order\Transaction
	 */
	protected function createTransaction (SubmitRequest $request)
	{
		return \App\Models\Order\Transaction::query()->create([
			'rzpOrderId' => $request->orderId,
			'paymentId' => $request->paymentId,
			'signature' => $request->signature,
			'verified' => true,
			'amountRequested' => $request->amount,
			'amountReceived' => $request->amount,
			'attempts' => 1,
			'status' => \App\Library\Enums\Transactions\Status::Paid
		]);
	}

	protected function sendRentalActiveResponse () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_CONFLICT, 'You already have an active rental for this video.'
		);
	}

	protected function sendRentalActivatedResponse () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_CREATED, 'You have successfully availed the video for rental viewing.'
		);
	}

	protected function sendCheckoutResponse (Transaction $transaction) : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare([
			'transactionId' => $transaction->id,
			'rzpOrderId' => $transaction->rzpOrderId
		], \Illuminate\Http\Response::HTTP_CREATED, 'We\'ve prepared your checkout experience.');
	}

	protected function createPlaceholderTransaction ($amount)
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
}