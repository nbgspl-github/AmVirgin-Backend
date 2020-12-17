<?php

namespace App\Http\Controllers\Api\Customer\Orders;

use App\Exceptions\ActionNotAllowedException;
use App\Http\Controllers\Api\ApiController;
use App\Models\Order;
use App\Resources\Orders\Customer\ListResource;
use App\Resources\Orders\Customer\OrderResource;
use App\Resources\Orders\Customer\OrderTrackingResource;
use Illuminate\Http\JsonResponse;

class OrderController extends ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index () : JsonResponse
	{
		return responseApp()->prepare(
			ListResource::collection($this->user()->orders()->paginate($this->paginationChunk()))->response()->getData(),
		);
	}

	public function show (Order $order) : JsonResponse
	{
		if ($order->customer != null && $order->customer->is($this->customer()))
			return responseApp()->prepare(
				new OrderResource($order)
			);
		throw new ActionNotAllowedException();
	}

	public function track (Order $order) : JsonResponse
	{
		if ($order->customer != null && $order->customer->is($this->customer()))
			return responseApp()->prepare(
				new OrderTrackingResource($order)
			);
		throw new ActionNotAllowedException();
	}

	protected function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}