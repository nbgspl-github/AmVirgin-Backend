<?php

namespace App\Http\Modules\Seller\Controllers\Api\Orders\Status\Handlers;

use App\Http\Modules\Seller\Controllers\Api\Orders\Status\Contracts\Action;
use App\Library\Enums\Orders\Status;
use App\Library\Http\AppResponse;
use App\Models\Auth\Seller;
use App\Models\Order\SubOrder;
use Illuminate\Http\Response;

class PendingDispatch implements Action
{
	public function rules () : array
	{
		return [

		];
	}

	public function allowed (SubOrder $order, Status $action, Status $next) : bool
	{
		return $action->isNot($next);
	}

	public function handle (SubOrder $order, Status $next, array $extra = []) : AppResponse
	{
		$order->update(array_merge(['status' => $next->value], $extra));
		return responseApp()->status(Response::HTTP_OK)->message('Action performed successfully!');
	}

	public function authorize (SubOrder $order, Seller $seller) : bool
	{
		return ($order->seller != null && $order->seller->is($seller));
	}
}