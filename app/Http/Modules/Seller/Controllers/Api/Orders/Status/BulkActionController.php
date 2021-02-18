<?php

namespace App\Http\Modules\Seller\Controllers\Api\Orders\Status;

use App\Exceptions\ValidationException;
use App\Http\Modules\Seller\Controllers\Api\Orders\Status\Contracts\Action;
use App\Http\Requests\Orders\Status\Bulk\UpdateRequest;
use App\Library\Enums\Orders\Status;
use App\Library\Utils\Extensions\Rule;
use App\Models\Order\SubOrder;
use BenSampo\Enum\Enum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BulkActionController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	protected array $handlers = [];

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER);
		$this->rules = [
			'status' => ['bail', 'required', Rule::in($this->allowed())]
		];
		$this->handlers = config('handlers.orders.seller');
	}

	/**
	 * @param UpdateRequest $request
	 * @return JsonResponse
	 * @throws \Exception
	 */
	public function handle (UpdateRequest $request) : JsonResponse
	{
		$action = $this->action();
		$handler = $this->handler($action->value);
		$extra = $this->validateExtra($handler->rules());
		$collection = $request->orders()->transform(function (SubOrder $order) use (&$handler, &$action, &$extra) {
			if (!$handler->authorize($order, $this->seller())) {
				return [
					'key' => $order->id,
					'code' => Response::HTTP_FORBIDDEN,
					'description' => 'This action is not allowed for this user at this time.'
				];
			} elseif (!$handler->allowed($order, $order->status, $action)) {
				return [
					'key' => $order->id,
					'code' => Response::HTTP_NOT_MODIFIED,
					'description' => 'This status is invalid/unavailable for this order at this time.'
				];
			} else {
				$handler->handle($order, $action, $extra);
				return [
					'key' => $order->id,
					'code' => Response::HTTP_OK,
					'description' => 'Successful.'
				];
			}
		});
		return responseApp()->prepare(
			$collection
		);
	}

	/**
	 * @return Status|array|Enum
	 * @throws ValidationException
	 */
	protected function action ()
	{
		return Status::coerce(($this->validate($this->rules))['status']);
	}

	/**
	 * @param $status
	 * @return Action
	 * @throws \Exception
	 */
	protected function handler ($status) : Action
	{
		$handler = $this->handlers[$status] ?? null;
		if ($handler != null)
			return new $handler();
		throw new \Exception('No handler is defined for this order action.');
	}

	/**
	 * @param array $rules
	 * @return array
	 * @throws ValidationException
	 */
	protected function validateExtra (array $rules) : array
	{
		return $this->validate($rules);
	}

	/**
	 * Enlist the actions for which bulk functionality can be invoked.
	 * @return array
	 */
	protected function allowed () : array
	{
		return [
			\App\Library\Enums\Orders\Status::ReadyForDispatch,
			\App\Library\Enums\Orders\Status::PendingDispatch,
			\App\Library\Enums\Orders\Status::OutForDelivery,
			\App\Library\Enums\Orders\Status::Cancelled,
			\App\Library\Enums\Orders\Status::Delivered,
		];
	}
}