<?php

namespace App\Http\Controllers\App\Seller\Orders;

use App\Http\Controllers\AppController;
use App\Models\SubOrder;
use App\Resources\Orders\Seller\ListResource;
use App\Resources\Orders\Seller\OrderResource;
use Illuminate\Http\JsonResponse;
use Throwable;

class OrderController extends AppController
{
	public function index (): JsonResponse
	{
		$response = responseApp();
		try {
			$query = SubOrder::query()->where('sellerId', $this->guard()->id());
			if (!empty(request('status'))) {
				$query->where('status', request('status'));
			}
			$resourceCollection = ListResource::collection($query->paginate());
			$response->status(HttpOkay)->setPayload($resourceCollection->response()->getData());
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function show (SubOrder $order): JsonResponse
	{
		$response = responseApp();
		try {
			$resource = new OrderResource($order);
			$response->status(HttpOkay)->message('Listing order details for given key.')->setPayload($resource);
		} catch (Throwable $exception) {
			$response->status(HttpResourceNotFound)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::SellerAPI);
	}
}