<?php

namespace App\Http\Modules\Customer\Controllers\Api\Orders;

use App\Exceptions\ValidationException;
use App\Library\Enums\Orders\Returns\Status;
use App\Library\Utils\Extensions\Rule;
use App\Library\Utils\Extensions\Time;
use App\Models\Order\Item;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ReturnController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_CUSTOMER);
		$this->rules = [
			'return' => [
				'action' => ['bail', 'required', Rule::in(['refund', 'replacement'])]
			]
		];
	}

	/**
	 * @param Item $item
	 * @return JsonResponse
	 * @throws ValidationException
	 */
	public function return (Item $item) : JsonResponse
	{
		$response = responseApp();
		$pending = $item->returns()->whereNotIn('status', [Status::Completed])->exists();
		$cancelled = $item->subOrder != null && $item->subOrder->status->is(\App\Library\Enums\Orders\Status::Cancelled);
		$delivered = $item->subOrder != null && ($item->subOrder->status->is(\App\Library\Enums\Orders\Status::Delivered) || !empty($item->subOrder->fulfilled_at));
		if (!$pending) {
			$response->status(Response::HTTP_CONFLICT)->message('You have already raised a return request for this item.');
		} elseif ($cancelled || !$delivered) {
			$response->status(Response::HTTP_NOT_MODIFIED)->message('This order is either cancelled or is yet to be delivered.');
		} else {
			$validated = $this->requestValid(request(), $this->rules['return']);
			if ($item->returnable && !$item->returnValidUntil->isPast()) {
				$item->returns()->create([
					'order_id' => $item->order->id,
					'customer_id' => $this->guard()->id(),
					'seller_id' => $item->sellerId,
					'order_segment_id' => $item->segment->id,
					'return_type' => $item->returnType == 'both' ? $validated['action'] : $item->returnType,
					'raised_at' => Time::mysqlStamp(),
				]);
				$response->status(Response::HTTP_CREATED)->message('You return request was raised successfully.');
			} else {
				$response->status(Response::HTTP_OK)->message('Your return request could not be raised as this item is either non-returnable or the return period is over.');
			}
		}
		return $response->send();
	}
}