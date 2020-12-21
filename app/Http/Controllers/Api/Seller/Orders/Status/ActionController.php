<?php

namespace App\Http\Controllers\Api\Seller\Orders\Status;

use App\Exceptions\ActionInvalidException;
use App\Exceptions\ActionNotAllowedException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\Seller\Orders\Status\Contracts\Action;
use App\Library\Enums\Orders\Status;
use App\Library\Utils\Extensions\Rule;
use App\Models\SubOrder;
use BenSampo\Enum\Enum;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ActionController extends ApiController
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
	 * @param SubOrder $order
	 * @return JsonResponse|void
	 * @throws ValidationException|ActionNotAllowedException|ActionInvalidException
	 * @throws \Exception
	 */
	public function handle (SubOrder $order) : JsonResponse
	{
		$action = $this->action();
		$handler = $this->handler($action->value);
		$extra = $this->validateExtra($handler->rules());
		if (!$handler->authorize($order, $this->seller())) {
			throw new ActionNotAllowedException('This action is not allowed for this user at this time.');
		}
		if (!$handler->allowed($order, $order->status, $action)) {
			throw new ActionInvalidException('This status is invalid/unavailable for this order at this time.');
		}
		return $handler->handle($order, $action, $extra)->send();
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
			\App\Library\Enums\Orders\Status::Dispatched,
			\App\Library\Enums\Orders\Status::OutForDelivery,
			\App\Library\Enums\Orders\Status::Cancelled,
			\App\Library\Enums\Orders\Status::Delivered,
		];
	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}