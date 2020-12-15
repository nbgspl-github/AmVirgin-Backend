<?php

namespace App\Http\Controllers\Api\Customer\Orders;

use App\Enums\Orders\Status;
use App\Http\Controllers\Api\ApiController;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CancellationController extends ApiController
{
	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
	}

	public function cancel (Order $order): JsonResponse
	{
		$response = responseApp();
		try {
			if ($order->customer != null && $order->customer->is($this->user()) && $this->cancellationAllowed($order)) {
				$this->performCancellation($order);
				$response->status(Response::HTTP_OK)->message('Your order was cancelled successfully!');
			} else {
				$response->status(Response::HTTP_NOT_MODIFIED)->message('Cancellation is not allowed on this order.');
			}
		} catch (\Throwable $e) {
			$response->status(Response::HTTP_INTERNAL_SERVER_ERROR)->message($e->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function cancellationAllowed (Order $order): bool
	{
		return !($order->status->is(Status::Cancelled) || $order->status->is(Status::Delivered));
	}

	protected function performCancellation (Order $order)
	{

	}

	protected function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}