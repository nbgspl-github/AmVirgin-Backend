<?php

namespace App\Http\Controllers\Api\Seller\Orders\Status;

use App\Classes\Rule;
use App\Enums\Orders\Status;
use App\Exceptions\ValidationException;
use App\Http\Requests\Orders\Status\Bulk\UpdateRequest;
use App\Models\SubOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Throwable;

class BulkStatusController extends AbstractStatusController
{
	public function update (UpdateRequest $request): JsonResponse
	{
		$response = responseApp();
		try {
			/**
			 * @var $order SubOrder
			 * @var $current string
			 * @var $new string
			 * @var $validated array
			 */
			$validated = $this->requestValid($request, $this->rules['update']);
			$new = $validated['status'];
			$failed = new Collection();
			$succeeded = new Collection();
			$unchanged = new Collection();
			$request->orders()->each(function (SubOrder $order) use ($new, $validated, &$failed, &$succeeded, &$unchanged) {
				$current = $order->status;
				if ($this->statusAllowed($current, $new)) {
					if (!$order->status->is($new)) {
						$this->performStatusUpdate($order, $new, $validated);
						$succeeded->push($order->id);
					} else {
						$unchanged->push($order->id);
					}
				} else {
					$failed->push($order->id);
				}
			});
			$response->status(HttpOkay)->message('Successfully processed all orders.')->setPayload(['failed' => $failed, 'succeeded' => $succeeded, 'unchanged' => $unchanged]);
		} catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function rulesByStatus ($status): array
	{
		return [
			'status' => ['bail', 'required', Rule::in(Status::getValues())],
		];
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
}