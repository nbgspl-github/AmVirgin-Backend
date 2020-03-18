<?php

namespace App\Http\Controllers\App\Customer;

use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\Order;
use App\Models\SellerOrder;
use Throwable;

class OrdersController extends ExtendedResourceController{

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$response = responseApp();
		try {
			$orders = Order::where([
				['customerId', $this->guard()->id()],
			])->get();
			$orders->transform(function (Order $order){
				return [
					'orderId' => $order->orderId(),
					'orderNumber' => $order->orderNumber(),
					'items' => $order->items,
				];
			});
			$response->status(HttpOkay)->message('Listing all orders for this customer.')->setValue('data', $orders);
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	protected function guard(){
	}
}