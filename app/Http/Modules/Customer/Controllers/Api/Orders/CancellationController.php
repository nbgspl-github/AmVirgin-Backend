<?php

namespace App\Http\Modules\Customer\Controllers\Api\Orders;

use App\Library\Enums\Orders\Status;
use App\Library\Utils\Extensions\Time;
use App\Models\Order\Order;
use App\Models\Order\SubOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CancellationController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
	}

	public function cancel (SubOrder $order) : JsonResponse
	{
		$response = responseApp();
		if ($order->customer != null && $order->customer->is($this->customer()) && $this->cancellationAllowed($order)) {
			$this->performCancellation($order);
			$response->status(Response::HTTP_OK)->message('Your order was cancelled successfully!');
		} else {
			$response->status(Response::HTTP_NOT_MODIFIED)->message('Cancellation is not allowed on this order.');
		}
		return $response->send();
	}

	protected function cancellationAllowed (SubOrder $order) : bool
	{
		return !($order->status->is(Status::Cancelled) || $order->status->is(Status::Delivered)) && empty($order->fulfilled_at);
	}

	protected function performCancellation (SubOrder $subOrder)
	{
		$subOrder->update([
			'status' => Status::Cancelled,
			'cancelled_at' => Time::mysqlStamp()
		]);
		$order = $subOrder->order;
		if ($order != null && ($order->subOrders()->where('status', Status::Cancelled)->count() == $order->subOrders()->count())) {
			$this->cancelMainOrder($order);
		}
	}

	protected function cancelMainOrder (Order $order)
	{
		$order->update([
			'status' => Status::Cancelled,
			'cancelled_at' => Time::mysqlStamp()
		]);
	}
}