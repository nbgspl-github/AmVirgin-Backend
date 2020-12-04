<?php

namespace App\Http\Controllers\App\Customer\Orders;

use App\Http\Controllers\AppController;
use App\Resources\Orders\Customer\OrderResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class OrderController extends AppController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index ()
	{
		$response = responseApp();
		try {
			$orders = $this->guard()->user()->orders;
			$orders = OrderResource::collection($orders);
			$response->status($orders->count() > 0 ? HttpOkay : HttpNoContent)->message('Listing all orders for this customer.')->setValue('data', $orders);
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function show ($id)
	{
		$response = responseApp();
		try {
			$order = $this->guard()->user()->orders()->where('id', $id)->firstOrFail();
			$order = new OrderResource($order);
			$response->status(HttpOkay)->message('Order details retrieved successfully.')->setValue('data', $order);
		} catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find order for that key.');
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::CustomerAPI);
	}
}