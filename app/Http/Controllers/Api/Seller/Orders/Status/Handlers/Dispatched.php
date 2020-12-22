<?php

namespace App\Http\Controllers\Api\Seller\Orders\Status\Handlers;

use App\Http\Controllers\Api\Seller\Orders\Status\Contracts\Action;
use App\Library\Enums\Orders\Status;
use App\Library\Http\Response\AppResponse;
use App\Library\Utils\Extensions\Rule;
use App\Library\Utils\Extensions\Time;
use App\Models\Auth\Seller;
use App\Models\Shipment;
use App\Models\SubOrder;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class Dispatched implements Action
{
	public function rules () : array
	{
		return [
			'fulfilledBy' => ['bail', 'required', Rule::in(['seller', 'amvirgin'])],
			'courierName' => ['bail', 'required_if:fulfilledBy,seller', 'nullable', 'string', 'max:255'],
			'airwayBillNumber' => ['bail', 'required_if:fulfilledBy,seller', 'nullable', 'string', 'max:255'],
		];
	}

	public function allowed (SubOrder $order, Status $action, Status $next) : bool
	{
		return $action->isNot($next);
	}

	public function handle (SubOrder $order, Status $next, array $extra = []) : AppResponse
	{
		$order->shipment()->associate(
			Shipment::query()->create(
				$this->courier($extra)
			)
		)->save();
		$order->update([
			'status' => $next->value,
			'dispatched_at' => Time::mysqlStamp(),
			'expected_at' => $this->expectedAt()
		]);
		return responseApp()->status(Response::HTTP_OK)->message('Action performed successfully!');
	}

	public function authorize (SubOrder $order, Seller $seller) : bool
	{
		return ($order->seller != null && $order->seller->is($seller));
	}

	protected function courier (array $extra) : array
	{
		if ($extra['fulfilledBy'] == 'amvirgin') {
			return [
				'courierName' => 'AmVirgin',
				'dispatched' => Time::mysqlStamp(),
				'fulfilled_by' => $extra['fulfilledBy'],
			];
		}
		return $extra;
	}

	protected function expectedAt () : string
	{
		return Carbon::now()->addDays(7)->format('Y-m-d H:i:s');
	}
}