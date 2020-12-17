<?php

namespace App\Http\Controllers\Api\Seller\Orders;

use App\Http\Controllers\Api\ApiController;
use App\Library\Enums\Orders\Status;
use App\Library\Utils\Extensions\Rule;
use App\Library\Utils\Extensions\Str;
use App\Models\SubOrder;
use App\Resources\Orders\Seller\ListResource;
use App\Resources\Orders\Seller\OrderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class OrderController extends ApiController
{
	public function __construct ()
	{
		parent::__construct();
		$this->rules = [
			'index' => [
				'status' => ['bail', 'sometimes', Rule::in(Status::getValues())]
			]
		];
	}

	public function index () : JsonResponse
	{
		$validated = $this->validate($this->rules['index']);
		return responseApp()->prepare(
			ListResource::collection($this->seller()->orders()->whereLike('status', $validated['status'] ?? Str::Empty)->paginate($this->paginationChunk()))->response()->getData()
		);
	}

	public function show (SubOrder $order) : JsonResponse
	{
		$response = responseApp();
		if ($order->seller != null && $order->seller->is($this->seller())) {
			$resource = new OrderResource($order);
			$response->status(Response::HTTP_OK)->message('Listing order details for given key.')->payload($resource);
		} else {
			$response->status(Response::HTTP_FORBIDDEN)->message('View details is not available for this order.')->payload();
		}
		return $response->send();
	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}