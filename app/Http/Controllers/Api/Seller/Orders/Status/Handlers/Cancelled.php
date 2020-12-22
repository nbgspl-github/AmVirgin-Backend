<?php

namespace App\Http\Controllers\Api\Seller\Orders\Status\Handlers;

use App\Http\Controllers\Api\Seller\Orders\Status\Contracts\Action;
use App\Library\Enums\Orders\Status;
use App\Library\Http\Response\AppResponse;
use App\Library\Utils\Extensions\Time;
use App\Models\Auth\Seller;
use App\Models\SubOrder;
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
			'cancelled_at' => Time::mysqlStamp()
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