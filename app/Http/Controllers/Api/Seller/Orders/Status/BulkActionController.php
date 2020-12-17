<?php

namespace App\Http\Controllers\Api\Seller\Orders\Status;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\Seller\Orders\Status\Contracts\Action;
use App\Http\Requests\Orders\Status\Bulk\UpdateRequest;
use App\Library\Enums\Orders\Status;
use App\Library\Utils\Extensions\Rule;
use App\Models\SubOrder;
use BenSampo\Enum\Enum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class BulkActionController extends ApiController
{
	protected array $handlers = [];

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER);
		$this->rules = [
			'status' => ['bail', 'required', Rule::in(Status::getValues())]
		];
		$this->handlers = config('handlers.orders.seller');
	}

	/**
	 * @param UpdateRequest $request
	 * @return JsonResponse|void
	 * @throws \Exception
	 */
	public function handle (UpdateRequest $request) : JsonResponse
	{
		$action = $this->action();
		$handler = $this->handler($action->value);
		$extra = $this->validateExtra($handler->rules());
		$failed = new Collection();
		$request->orders()->each(function (SubOrder $order) use (&$handler, &$action, &$extra, &$failed) {
			if (!$handler->authorize($order, $this->seller())) {
				$failed = $failed->push([$order->id => 'This action is not allowed for this user at this time.']);
			} elseif (!$handler->allowed($order, $order->status, $action)) {
				$failed = $failed->push([$order->id => 'This status is invalid/unavailable for this order at this time.']);
			} else {
				$handler->handle($order, $action, $extra);
			}
		});
		return responseApp()->prepare(
			['failed' => $failed],
			Response::HTTP_OK,
			'Successfully processed all orders!'
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

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}