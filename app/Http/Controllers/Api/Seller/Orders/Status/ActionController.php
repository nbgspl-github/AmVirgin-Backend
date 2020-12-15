<?php

namespace App\Http\Controllers\Api\Seller\Orders\Status;

use App\Classes\Rule;
use App\Enums\Orders\Status;
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
	 * @throws ValidationException|ActionNotAllowedException
	 */
	public function handle (SubOrder $order) : JsonResponse
	{
		$status = $this->status();
		$handler = $this->handler($status);
		$extra = $this->validateExtra($handler->rules());
		if ($handler->authorize($order, $this->user()) && $handler->allowed($order, $order->status, $status)) {
			return $handler->handle($order, $status, $extra)->send();
		}
	}

	/**
	 * @return Status|array|Enum
	 * @throws ValidationException
	 */
	protected function status ()
	{
		return Status::coerce(($this->requestValid(request(), $this->rules))['status']);
	}

	protected function handler ($status) : ?Action
	{
		return $this->handlers[$status] ?? null;
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