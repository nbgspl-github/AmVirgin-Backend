<?php

namespace App\Http\Controllers\Api\Customer\Orders;

use App\Http\Controllers\Api\ApiController;
use App\Models\Order;
use App\Resources\Orders\Customer\OrderResource;
use App\Resources\Orders\Customer\OrderTrackingResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class OrderController extends ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index () : JsonResponse
	{
		$response = responseApp();
		$resourceCollection = OrderResource::collection($this->user()->orders()->paginate($this->paginationChunk()));
		$response->status(Response::HTTP_OK)
			->message("Found {$resourceCollection->count()} orders.")
			->setPayload($resourceCollection->response()->getData());
		return $response->send();
	}

	public function show (Order $order) : JsonResponse
	{
		$response = responseApp();
		$order = new OrderResource($order);
		$response->status(Response::HTTP_OK)
			->message('Order details retrieved successfully.')
			->setValue('data', $order);
		return $response->send();
	}

	public function track (Order $order) : JsonResponse
	{
		$response = responseApp();
		$order = new OrderTrackingResource($order);
		$response->status(HttpOkay)->message('Tracking details retrieved successfully.')->setValue('data', $order);
		return $response->send();
	}

	protected function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}