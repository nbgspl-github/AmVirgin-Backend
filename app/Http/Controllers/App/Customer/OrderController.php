<?php

namespace App\Http\Controllers\App\Customer;

use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\Order;
use App\Models\SellerOrder;
use App\Resources\Orders\Customer\OrderResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class OrderController extends ExtendedResourceController{
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$response = responseApp();
		try {
			$orders = $this->guard()->user()->orders;
			$orders->transform(function (Order $order){
				return [
					'orderId' => $order->orderId(),
					'orderNumber' => $order->orderNumber(),
					'items' => $order->items,
				];
			});
			$response->status($orders->count() > 0 ? HttpOkay : HttpNoContent)->message('Listing all orders for this customer.')->setValue('data', $orders);
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function show($id){
		$response = responseApp();
		try {
			$order = Order::retrieveThrows($id);
			$order = new OrderResource($order);
			$response->status(HttpOkay)->message('Order details retrieved successfully.')->setValue('data', $order);
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message($exception->getMessage());
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	protected function guard(){
		return auth('customer-api');
	}
}