<?php

namespace App\Http\Controllers\Api\Seller\Orders;

use App\Http\Controllers\Api\ApiController;
use App\Models\SubOrder;
use App\Resources\Orders\Seller\ListResource;
use App\Resources\Orders\Seller\OrderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class OrderController extends ApiController
{
	public function index () : JsonResponse
	{
		$response = responseApp();
		try {
			$query = SubOrder::query()->where('sellerId', $this->guard()->id());
			if (!empty(request('status'))) {
				$query->where('status', request('status'));
			}
			$resourceCollection = ListResource::collection($query->paginate());
			$response->status(HttpOkay)->payload($resourceCollection->response()->getData());
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function show (SubOrder $order) : JsonResponse
	{
		$response = responseApp();
		try {
			if ($order->seller != null && $order->seller->is($this->user())) {
				$resource = new OrderResource($order);
				$response->status(HttpOkay)->message('Listing order details for given key.')->payload($resource);
			} else {
				$response->status(Response::HTTP_FORBIDDEN)->message('View details is not available for this order.')->payload();
			}
		} catch (Throwable $exception) {
			$response->status(HttpResourceNotFound)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}