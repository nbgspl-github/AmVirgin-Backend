<?php

namespace App\Http\Controllers\Api\Seller\Orders\Status;

use App\Classes\Rule;
use App\Enums\Orders\Status;
use App\Exceptions\ActionInvalidException;
use App\Exceptions\ActionNotAllowedException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\Seller\Orders\Status\Contracts\Action;
use App\Models\SubOrder;
use App\Traits\ValidatesRequest;
use BenSampo\Enum\Enum;
use Illuminate\Http\JsonResponse;

class ActionController extends ApiController
{
	use ValidatesRequest;

	protected array $handlers = [];

	protected array $rules = [];

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER_API);
		$this->rules = [
			'status' => ['bail', 'required', Rule::in(Status::getValues())]
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
		return Status::coerce(($this->requestValid(request(), $this->rules))['status']);
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
		return $this->requestValid(request(), $rules);
	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}