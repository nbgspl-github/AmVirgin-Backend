<?php

namespace App\Http\Controllers\Api\Seller\Orders\Status;

use App\Exceptions\ValidationException;
use App\Library\Enums\Orders\Status;
use App\Library\Utils\Extensions\Rule;
use App\Models\SubOrder;
use Illuminate\Http\JsonResponse;
use Throwable;

class StatusController extends AbstractStatusController
{
	public function update (SubOrder $order) : JsonResponse
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
				$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Order status updated successfully.');
			} else {
				$response->status(\Illuminate\Http\Response::HTTP_NOT_MODIFIED)->message('Requested order status is invalid for current active status.');
			}
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function statusAllowed (string $current, string $next): bool
	{
		return true;
	}

	protected function rulesByStatus ($status): array
	{
		$map = [
			Status::ReadyForDispatch => [
				'status' => ['bail', 'required', Rule::in(Status::getValues())],
			],
			Status::Dispatched => [
				'status' => ['bail', 'required', Rule::in(Status::getValues())],
				'shippingMethod' => ['bail', Rule::in(['seller', 'seller-smart'])],
				'courierName' => ['bail', 'required_if:shippingMethod,seller', 'nullable', 'string', 'max:255'],
				'airwayBillNumber' => ['bail', 'required_if:shippingMethod,seller', 'nullable', 'string', 'max:255'],
				'dispatchedOn' => ['bail', 'required_if:shippingMethod,seller', 'nullable', 'date_format:Y-m-d H:i:s']
			]
		];
		return $map[$status] ?? ['status' => ['bail', 'required', Rule::in(Status::getValues())]];
	}

	protected function closures (): array
	{
		return [
			Status::Dispatched => function (SubOrder $order, array $payload) {
				$order->update($payload);
			},
			Status::ReadyForDispatch => function (SubOrder $order, array $payload) {
				$order->update($payload);
			},
		];
	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}