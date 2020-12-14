<?php

namespace App\Http\Controllers\App\Customer\Orders;

use App\Classes\Rule;
use App\Classes\Time;
use App\Enums\Orders\Returns\Status;
use App\Exceptions\ValidationException;
use App\Http\Controllers\AppController;
use App\Models\OrderItem;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ReturnController extends AppController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AuthCustomer);
		$this->rules = [
			'return' => [
				'action' => ['bail', 'required', Rule::in(['refund', 'replacement'])]
			]
		];
	}

	public function return (OrderItem $item): JsonResponse
	{
		$response = responseApp();
		try {
			$pending = $item->returns()->whereNotIn('status', [Status::Completed])->exists();
			if (!$pending) {
				$response->status(Response::HTTP_CONFLICT)->message('You have already raised a return request for this item.');
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
					$response->status(HttpCreated)->message('You return request was raised successfully.');
				} else {
					$response->status(HttpOkay)->message('Your return request could not be raised as this item is either non-returnable or the return period is over.');
				}
			}
		} catch (ValidationException $e) {
			$response->status(Response::HTTP_BAD_REQUEST)->message($e->getError());
		} catch (\Throwable $e) {
			$response->status(HttpServerError)->message($e);
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::CustomerAPI);
	}
}