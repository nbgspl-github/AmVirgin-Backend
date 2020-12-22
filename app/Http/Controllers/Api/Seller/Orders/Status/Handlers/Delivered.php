<?php

namespace App\Http\Controllers\Api\Seller\Orders\Status\Handlers;

use App\Http\Controllers\Api\Seller\Orders\Status\Contracts\Action;
use App\Library\Enums\Orders\Status;
use App\Library\Http\Response\AppResponse;
use App\Models\Auth\Seller;
use App\Models\SubOrder;
use Illuminate\Http\Response;

class Delivered implements Action
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