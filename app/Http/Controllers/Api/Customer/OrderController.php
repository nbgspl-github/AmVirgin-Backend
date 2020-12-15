<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\ApiController;
use App\Models\Order;
use App\Resources\Orders\Customer\OrderResource;
use App\Resources\Orders\Customer\OrderTrackingResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class OrderController extends ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index (): JsonResponse
	{
		$response = responseApp();
		try {
			$orders = $this->guard()->user()->orders;
			$orders = OrderResource::collection($orders);
			$response->status(Response::HTTP_OK)->message('Listing all orders for this customer.')->setValue('data', $orders);
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function show (Order $order): JsonResponse
	{
		$response = responseApp();
		try {
			$order = new OrderResource($order);
			$response->status(Response::HTTP_OK)->message('Order details retrieved successfully.')->setValue('data', $order);
		} catch (Throwable $exception) {
			$response->status(Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function track (Order $order): JsonResponse
	{
		$response = responseApp();
		try {
			$order = new OrderTrackingResource($order);
			$response->status(HttpOkay)->message('Tracking details retrieved successfully.')->setValue('data', $order);
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth('customer-api');
	}
}