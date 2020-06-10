<?php

namespace App\Http\Controllers\App\Seller;

use App\Classes\Time;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\Auth\Customer;
use App\Models\SellerOrder;
use App\Resources\Orders\Seller\ListResource;
use App\Resources\Orders\Seller\OrderResource;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Throwable;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Resources\Products\Customer\ImageResource;
use Illuminate\Support\Facades\Config;

class OrderController extends ExtendedResourceController{
	use ValidatesRequest;

	protected array $rules;

	public function __construct(){
		parent::__construct();
		$this->rules = [

		];
	}

	public function index(): JsonResponse{
		$response = responseApp();
		try {
			$orderCollection = SellerOrder::startQuery()->useAuth()->get();
			$resourceCollection = ListResource::collection($orderCollection);
			$response->status(HttpOkay)->message('Listing all orders for this seller.')->setValue('data', $resourceCollection);
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function show($id): JsonResponse{
		$response = responseApp();
		try {
			$order = SellerOrder::startQuery()->useAuth()->key($id)->firstOrFail();
			$resource = new OrderResource($order);
			$response->status(HttpOkay)->message('Listing order details for given key.')->setValue('payload', $resource);
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message($exception->getMessage());
		}
		catch (Throwable $exception) {
			$response->status(HttpResourceNotFound)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function updateOrderStatus($id = '', $status = ''){
		$response = responseApp();
		try {
			$data = Order::find($id);
			if (!empty($data)) {

				$order_status = Order::getAllStatus();//Config::get('app.order_status');

				if (in_array($status, $order_status)) {
					$data->update([
						'status' => $status,
					]);
					$response->status(HttpOkay)->message('Status Updated Successfully');
				}
				else {
					$response->status(HttpOkay)->message('Status did not matched in our record');
				}
			}
			else {
				$response->status(HttpResourceNotFound)->message('Order Not Found');
			}

		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function getOrderStatus(){
		$response = responseApp();
		try {
			$order_status = Order::getAllStatus();
			$response->status(HttpOkay)->message('Status listing for orders.')->setValue('data', $order_status);
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function getOrderByStatus($status){
		$response = responseApp();
		try {
			// $order_status= Config::get('app.order_status');
			$order_status = Order::getAllStatus();

			if (in_array($status, $order_status)) {

				$ordersData = $total = [];

				$ordersData = Order::where(['status' => $status])
					->get();

				if (!empty(count($ordersData))) {
					$total['subTotal'] = $ordersData->sum('subTotal');
					$total['tax'] = $ordersData->sum('tax');
					$total['total'] = $ordersData->sum('total');

					$ordersData['total'] = $total;
				}

				$response->status(HttpOkay)->message("Orders Listing for '$status' status")->setValue('data', $ordersData);
			}
			else {
				$response->status(HttpOkay)->message('Status did not matched in our record');
			}

		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	protected function guard(){
		return auth('seller-api');
	}
}