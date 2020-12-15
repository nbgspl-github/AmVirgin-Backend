<?php

namespace App\Http\Controllers\Api\Seller\Orders\Status\Handlers;

use App\Classes\Builders\ResponseBuilder;
use App\Classes\Rule;
use App\Classes\Time;
use App\Enums\Orders\Status;
use App\Http\Controllers\Api\Seller\Orders\Status\Contracts\Action;
use App\Models\Auth\Seller;
use App\Models\Shipment;
use App\Models\SubOrder;
use Illuminate\Http\Response;

class Dispatched implements Action
{
	public function rules () : array
	{
		return [
			'shippingMethod' => ['bail', Rule::in(['seller', 'seller-smart'])],
			'courierName' => ['bail', 'required_if:shippingMethod,seller', 'nullable', 'string', 'max:255'],
			'airwayBillNumber' => ['bail', 'required_if:shippingMethod,seller', 'nullable', 'string', 'max:255'],
		];
	}

	public function allowed (SubOrder $order, Status $action, Status $next) : bool
	{
		return $action->isNot($next);
	}

	public function handle (SubOrder $order, Status $next, array $extra = []) : ResponseBuilder
	{
		$order->shipment()->associate(
			Shipment::query()->create(
				$this->courier($extra)
			)
		)->save();
		$order->update([
			'status' => $next->value,
			'dispatched_at' => Time::mysqlStamp(),
		]);
		return responseApp()->status(Response::HTTP_OK)->message('Action performed successfully!');
	}

	public function authorize (SubOrder $order, Seller $seller) : bool
	{
		return ($order->seller != null && $order->seller->is($seller));
	}

	protected function courier (array $extra) : array
	{
		if ($extra['shippingMethod'] == 'seller-smart') {
			return [
				'courierName' => $extra['courierName'],
				'awb' => $extra['airwayBillNumber'],
				'dispatched' => Time::mysqlStamp(),
				'shippingMethod' => $extra['shippingMethod'],
			];
		}
		return $extra;
	}
}