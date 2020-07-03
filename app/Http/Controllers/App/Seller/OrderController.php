<?php

namespace App\Http\Controllers\App\Seller;

use App\Classes\Arrays;
use App\Enums\Seller\OrderStatus;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\Order;
use App\Models\SellerOrder;
use App\Resources\Orders\Seller\ListResource;
use App\Resources\Orders\Seller\OrderResource;
use App\Traits\ValidatesRequest;
use BenSampo\Enum\Exceptions\InvalidEnumMemberException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Throwable;

class OrderController extends ExtendedResourceController {
	use ValidatesRequest;

	protected array $rules;

	public function __construct () {
		parent::__construct();
		$this->rules = [

		];
	}

	public function index () : JsonResponse {
		$response = responseApp();

		$per_page = request()->get('per_page') ?? '';
		$page_no = request()->get('page') ?? '1';
		if (empty($per_page)) {
			$per_page = 10;
		}
		try {
			$orderCollection = SellerOrder::startQuery()->withRelations('order')->useAuth()->paginate($per_page);

			$total = count($orderCollection);
			$totalRec = $orderCollection->total(); 
			$meta = [
					'pagination' => [
						'pages' => countRequiredPages($totalRec, $per_page),
						'current_page' => $page_no,
						'items' => ['total' => $total, 'totalRec' => $totalRec, 'chunk' => $per_page], 
					],
				];
			if (request()->has('status') && !empty(request('status'))) {
				try {
					$status = new OrderStatus(request('status'));
					$status = $status->value;
					$orderCollection = $orderCollection->filter(static function (SellerOrder $sellerOrder) use ($status) {
						$order = $sellerOrder->order;
						if ($order != null && $order->status == $status)
							return true;
						else
							return false;
					})->values();
					
					$resourceCollection = ListResource::collection($orderCollection);
					$response->status(HttpOkay)->message('Listing all orders for this seller.')->setValue('meta', $meta)->setValue('data', $resourceCollection);
				}
				catch (InvalidEnumMemberException $exception) {
					$response->status(HttpOkay)->message('Invalid status value for filter.');
				}
				catch (Throwable $exception) {
					$response->status(HttpOkay)->message($exception->getMessage());
				}
			}
			else {
				$resourceCollection = ListResource::collection($orderCollection);
				$response->status(HttpOkay)->message('Listing all orders for this seller.')->setValue('meta', $meta)->setValue('data', $resourceCollection);
			}
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function show ($id) : JsonResponse {
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
	public function orderDetails($id) : JsonResponse {
		$response = responseApp();
		try { 
			$order = SellerOrder::startQuery()->useAuth()->key($id)->firstOrFail();
			$order->status ='pending-dispatch';
			$order->update();
			$orderStatus = Order::where('id',$id)
								->update(['status'=>"pending-dispatch"]);
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

	public function updateStatus (int $id) : JsonResponse {
		$response = responseApp();
		$status = request('status');
		try {
			$order = Order::query()->whereKey($id)->firstOrFail();
			$transitions = OrderStatus::transitions(new OrderStatus($order->status));
			if (!empty($status) && Arrays::contains($transitions, $status, true)) {
				$order->update([
					'status' => $status,
				]);
				$response->status(HttpOkay)->message('Order status updated successfully.');
			}
			else {
				$response->status(HttpOkay)->message('Requested order status is invalid for current active status.');
			}
		}
		catch (InvalidEnumMemberException $exception) {
			$response->status(HttpOkay)->message('Current order status is invalid.');
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find order for that key.');
		}
		catch (Throwable $exception) {
			$response->status(HttpResourceNotFound)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function getOrderStatus () {
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

	public function getOrderByStatus ($status) {
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

	protected function guard () {
		return auth('seller-api');
	}
}