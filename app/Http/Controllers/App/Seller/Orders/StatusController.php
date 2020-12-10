<?php

namespace App\Http\Controllers\App\Seller\Orders;

use App\Classes\Rule;
use App\Enums\Orders\Status;
use App\Exceptions\ValidationException;
use App\Http\Controllers\AppController;
use App\Models\SubOrder;
use App\Traits\ValidatesRequest;
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
	 * @var \Closure[]|array
	 */
	protected array $closures;

	public function __construct ()
	{
		parent::__construct();
		$this->rules = [
			'update' => $this->rulesByStatus()
		];
		$this->closures = [
			Status::Dispatched => function (SubOrder $order, array $payload) {
				$order->update($payload);
			},
			Status::ReadyForDispatch => function (SubOrder $order, array $payload) {
				$order->update($payload);
			},
		];
	}

	public function update (SubOrder $order): JsonResponse
	{
		$response = responseApp();
		try {
			/**
			 * @var $order SubOrder
			 * @var $current string
			 * @var $new string
			 * @var $validated array
			 */
			$validated = $this->requestValid(request(), $this->rules['update']);
			$new = $validated['status'];
			$current = $order->status;
			if ($this->statusAllowed($current, $new)) {
				$this->performStatusUpdate($order, $new, $validated);
				$response->status(HttpOkay)->message('Order status updated successfully.');
			} else {
				$response->status(HttpNotModified)->message('Requested order status is invalid for current active status.');
			}
		} catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(HttpResourceNotFound)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function statusAllowed (string $current, string $next): bool
	{
		return true;
	}

	protected function performStatusUpdate (SubOrder $order, string $new, array $payload)
	{
		if (isset($this->closures[$new])) {
			$closure = $this->closures[$new];
			call_user_func($closure, $order, $payload);
		}
		$order->update([
			'status' => $new
		]);
	}

	protected function rulesByStatus (): array
	{
		return [
			'status' => ['bail', 'required', Rule::in(Status::getValues())],
			'shippingMethod' => ['bail', Rule::requiredIf(function () {
				return request('status') == Status::Dispatched;
			}), Rule::in('seller', 'seller-smart')],
			'courierName' => ['bail', Rule::requiredIf(function () {
				return request('status') == Status::Dispatched && request('shippingMethod') == 'seller';
			}), 'string', 'max:255'],
			'airwayBillNumber' => ['bail', Rule::requiredIf(function () {
				return request('status') == Status::Dispatched && request('shippingMethod') == 'seller';
			}), 'string', 'max:255'],
			'dispatchedOn' => ['bail', Rule::requiredIf(function () {
				return request('status') == Status::Dispatched && request('shippingMethod') == 'seller';
			}), 'date_format:Y-m-d H:i:s']
		];
	}

	protected function guard ()
	{
		return auth(self::SellerAPI);
	}
}