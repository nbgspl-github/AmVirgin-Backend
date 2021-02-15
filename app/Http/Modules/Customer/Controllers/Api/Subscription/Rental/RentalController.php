<?php

namespace App\Http\Modules\Customer\Controllers\Api\Subscription\Rental;

use App\Http\Modules\Customer\Requests\Subscription\Rental\StoreRequest;
use App\Http\Modules\Customer\Resources\Subscription\Rental\VideoResource;

class RentalController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_CUSTOMER);
	}

	public function index () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			VideoResource::collection($this->customer()->activeRentals()->get())
		);
	}

	public function store (StoreRequest $request, \App\Models\Video\Video $video) : \Illuminate\Http\JsonResponse
	{
		if (!$this->customer()->isRented($video) && $this->customer()->isRentalExpired($video)) {
			$transaction = $this->createTransaction($request);
			$this->customer()->addRentalVideo($video, $transaction);
			return $this->sendRentalActivatedResponse();
		} else {
			return $this->sendRentalActiveResponse();
		}
	}

	/**
	 * @param StoreRequest $request
	 * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|\App\Models\Order\Transaction
	 */
	protected function createTransaction (StoreRequest $request)
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
}