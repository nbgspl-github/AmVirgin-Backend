<?php

namespace App\Http\Controllers\App\Seller\Orders;

use App\Classes\Rule;
use App\Enums\Seller\OrderStatus;
use App\Exceptions\ValidationException;
use App\Http\Controllers\AppController;
use App\Models\Order;
use App\Traits\ValidatesRequest;
use BenSampo\Enum\Exceptions\InvalidEnumMemberException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Throwable;

class StatusController extends AppController
{
	use ValidatesRequest;

	/**
	 * @var array|\array[][]
	 */
	protected array $rules;

	/**
	 * @var \Closure[]
	 */
	protected array $closures;

	public function __construct ()
	{
		parent::__construct();
		$this->rules = [
			'update' => [
				'status' => ['bail', 'required', Rule::in(OrderStatus::getValues())],
				'shippingMethod' => ['bail', Rule::requiredIf(function () {
					return request('status') == OrderStatus::Dispatched;
				}), Rule::in('seller', 'seller-smart')],
				'courierName' => ['bail', Rule::requiredIf(function () {
					return request('status') == OrderStatus::Dispatched && request('shippingMethod') == 'seller';
				}), 'string', 'max:255'],
				'airwayBillNumber' => ['bail', Rule::requiredIf(function () {
					return request('status') == OrderStatus::Dispatched && request('shippingMethod') == 'seller';
				}), 'string', 'max:255'],
				'dispatchedOn' => ['bail', Rule::requiredIf(function () {
					return request('status') == OrderStatus::Dispatched && request('shippingMethod') == 'seller';
				}), 'date_format:Y-m-d H:i:s']
			]
		];
		$this->closures = [
			OrderStatus::Dispatched => function (Order $order, array $payload) {
				$order->update($payload);
				$order->sellerOrder()->update($payload);
			},
		];
	}

	public function update (int $id): JsonResponse
	{
		$response = responseApp();
		try {
			/**
			 * @var $order Order
			 * @var $current string
			 * @var $new string
			 * @var $validated array
			 */
			$order = Order::query()->whereKey($id)->firstOrFail();
			$validated = $this->requestValid(request(), $this->rules['update']);
			$new = $validated['status'];
			$current = $order->status;
			if ($this->statusAllowed($current, $new)) {
				$this->performStatusUpdate($order, $new, $validated);
				$response->status(HttpOkay)->message('Order status updated successfully.');
			} else {
				$response->status(HttpOkay)->message('Requested order status is invalid for current active status.');
			}
		} catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getError());
		} catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find order for that key.');
		} catch (Throwable $exception) {
			$response->status(HttpResourceNotFound)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function statusAllowed (string $current, string $next)
	{
		try {
			$transitions = OrderStatus::transitions(new OrderStatus($current));
//			return Arrays::contains($transitions, $next, true);
			return true;
		} catch (InvalidEnumMemberException $exception) {
			return false;
		}
	}

	protected function performStatusUpdate (Order $order, string $new, array $payload)
	{
		if (isset($this->closures[$new])) {
			$closure = $this->closures[$new];
			call_user_func($closure, $order, $payload);
		}
		$order->update([
			'status' => $new
		]);
		$order->sellerOrder()->update([
			'status' => $new
		]);
	}

	protected function guard ()
	{
		return auth(self::SellerAPI);
	}
}