<?php

namespace App\Http\Modules\Seller\Controllers\Api\Orders\Status\Handlers;

use App\Http\Modules\Seller\Controllers\Api\Orders\Status\Contracts\Action;
use App\Library\Enums\Orders\Status;
use App\Library\Http\AppResponse;
use App\Library\Utils\Extensions\Time;
use App\Models\Auth\Seller;
use App\Models\Order\SubOrder;
use Illuminate\Http\Response;

class Cancelled implements Action
{
	public function rules () : array
	{
		return [

		];
	}

	public function allowed (SubOrder $order, Status $action, Status $next) : bool
	{
		return (
			$action->isNot($next)
			&&
			!($order->status->is(Status::Cancelled) || $order->status->is(Status::Delivered))
			&&
			empty($order->fulfilled_at)
		);
	}

	public function handle (SubOrder $order, Status $next, array $extra = []) : AppResponse
	{
		$order->update([
			'status' => Status::Cancelled,
			'cancelled_at' => Time::mysqlStamp(),
			'cancelled_by' => 'Seller',
			'cancellation_reason' => 'Seller cancelled the order.'
		]);
		$main = $order->order;
		if ($main != null && ($main->subOrders()->where('status', Status::Cancelled)->count() == $main->subOrders()->count())) {
			$main->update([
				'status' => Status::Cancelled,
				'cancelled_at' => Time::mysqlStamp()
			]);
		}
		return responseApp()->status(Response::HTTP_OK)->message('Action performed successfully!');
	}

	public function authorize (SubOrder $order, Seller $seller) : bool
	{
		return ($order->seller != null && $order->seller->is($seller));
	}
}