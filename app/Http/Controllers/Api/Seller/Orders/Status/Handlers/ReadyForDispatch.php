<?php

namespace App\Http\Controllers\Api\Seller\Orders\Status\Handlers;

use App\Classes\Builders\ResponseBuilder;
use App\Enums\Orders\Status;
use App\Exceptions\ActionNotAllowedException;
use App\Http\Controllers\Api\Seller\Orders\Status\Contracts\Action;
use App\Models\Auth\Seller;
use App\Models\SubOrder;
use Illuminate\Http\Response;

class ReadyForDispatch implements Action
{
	public function rules () : array
	{
		return [

		];
	}

	public function allowed (SubOrder $order, Status $current, Status $next) : bool
	{
		if ($current->isNot($next)) {
			return true;
		} else {
			throw new ActionNotAllowedException('This action is not allowed for this order at this time.');
		}
	}

	public function handle (SubOrder $order, Status $next, array $extra = []) : ResponseBuilder
	{
		$order->update(array_merge(['status' => $next->value], $extra));
		return responseApp()->status(Response::HTTP_OK)->message('Action performed successfully!');
	}

	public function authorize (SubOrder $order, Seller $seller) : bool
	{
		$authorized = $order->seller != null && $order->seller->is($seller);
		if (!$authorized)
			throw new ActionNotAllowedException('This action is not allowed for this order at this time.');
		else
			return true;
	}
}