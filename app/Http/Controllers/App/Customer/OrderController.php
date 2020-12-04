<?php

namespace App\Http\Controllers\App\Customer;

use App\Enums\Seller\OrderStatus;
use App\Http\Controllers\AppController;
use App\Models\Order;
use App\Resources\Orders\Customer\OrderResource;
use App\Resources\Orders\Customer\OrderTrackingResource;
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

	public function track ($id)
	{
		$response = responseApp();
		try {
			$order = $this->guard()->user()->orders()->where('id', $id)->firstOrFail();
			$order = new OrderTrackingResource($order);
			$response->status(HttpOkay)->message('Tracking details retrieved successfully.')->setValue('data', $order);
		} catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find order for that key.');
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function cancel ($id)
	{
		$response = responseApp();
		try {
			/**
			 * @var $order Order
			 */
			$order = $this->guard()->user()->orders()->whereNotIn('status', [OrderStatus::Delivered, OrderStatus::Cancelled, OrderStatus::RefundProcessing])->where('id', $id)->firstOrFail();
			if ($order != null) {
				$order->update([
					'status' => OrderStatus::Cancelled
				]);
				$order->sellerOrder()->update([
					'status' => OrderStatus::Cancelled,
					'cancellationReason' => request('cancellationReason', 'Order cancelled by customer.')
				]);
			}
			$response->status(HttpOkay)->message('Order cancelled successfully.');
		} catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find order for that key.');
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function return ($id)
	{
		$response = responseApp();
		try {
			/**
			 * @var $order Order
			 */
			$order = $this->guard()->user()->orders()->where('status', OrderStatus::Delivered)->where('id', $id)->firstOrFail();
			if ($order != null) {
				$order->update([
					'status' => OrderStatus::ReturnInitiated
				]);
				$order->sellerOrder()->update([
					'status' => OrderStatus::ReturnInitiated,
				]);
			}
			$response->status(HttpOkay)->message('Return initiated successfully.');
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
		return auth('customer-api');
	}
}