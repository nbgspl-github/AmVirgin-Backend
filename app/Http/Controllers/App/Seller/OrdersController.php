<?php

namespace App\Http\Controllers\App\Seller;

use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\Customer;
use App\Models\SellerOrder;
use App\Traits\ValidatesRequest;
use Throwable;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Resources\Products\Customer\ProductImageResource;
class OrdersController extends ExtendedResourceController {
	use ValidatesRequest;

	protected array $rules;

	public function __construct() {
		parent::__construct();
		$this->rules = [

		];
	}

	public function index() {
		$response = responseApp();
		try {
			$orders = SellerOrder::where([
				['sellerId', $this->guard()->id()],
			])->get();
			$orders->transform(function (SellerOrder $sellerOrder) {
				return [
					'orderId' => $sellerOrder->orderId(),
					'orderNumber' => $sellerOrder->orderNumber(),
					'customerId' => $sellerOrder->customerId(),
					'customer' => $sellerOrder->customer,
					'items' => $sellerOrder->items,

				];
			});
			$response->status(HttpOkay)->message('Listing all orders for this seller.')->setValue('data', $orders);
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}
	public function getorders() {
		$response = responseApp();
		$user = auth('customer-api')->user()->id;
		// DB::enableQueryLog(); 
		try {
			$orders = Order::with('customer','items')
			->where([
				['customerId', $user],
			])->get();  

			// $orders = new ProductImageResource($orders);
			// $payload = $orders->jsonSerialize();
			// print_r($orders);die();
			$response->status(HttpOkay)->message('Listing all orders for this Customer.')->setValue('data', $orders);
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function getorderdetails($id) {
		$response = responseApp();
		$user = auth('customer-api')->user()->id;
		// DB::enableQueryLog(); 
		try {
			$orders = Order::with('customer','items','address')
			->where([
				['customerId', $user],['id', $id],
			])->get();   

			$response->status(HttpOkay)->message('Order details for this Customer and this order id.')->setValue('data', $orders);
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function getOrdersDetails($id) {
		$response = responseApp();
		$user = auth('seller-api')->user()->id;
		// DB::enableQueryLog(); 
		try {
			$orders = Order::with('customer','items','address')
			->where([
				['customerId', $user],['id', $id],
			])->get();   

			$response->status(HttpOkay)->message('Order details for this Customer and this order id.')->setValue('data', $orders);
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	} 

	public function customer($id) {
		$response = responseApp();
		try {
			$customer = Customer::retrieve($id);
			$response->status(HttpOkay)->message('Customer Details.')->setValue('data', $customer);
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	protected function guard() {
		return auth('seller-api');
	}
}