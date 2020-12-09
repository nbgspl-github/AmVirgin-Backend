<?php

namespace App\Http\Controllers\App\Customer\Orders;

use App\Classes\Time;
use App\Enums\Orders\Returns\Status;
use App\Http\Controllers\AppController;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;

class ReturnController extends AppController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AuthCustomer);
	}

	public function return (OrderItem $item): JsonResponse
	{
		$response = responseApp();
		try {
			$pending = $item->returns()->whereNotIn('status', [Status::Completed])->exists();
			if (!$pending) {
				$response->status(HttpResourceAlreadyExists)->message('You have already raised a return request for this item.');
			} else {
				if ($item->returnable && !$item->returnValidUntil->isPast()) {
					$item->returns()->create([
						'order_id' => $item->order->id,
						'customer_id' => $this->guard()->id(),
						'seller_id' => $item->sellerId,
						'order_segment_id' => $item->segment->id,
						'return_type' => $item->returnType,
						'raised_at' => Time::mysqlStamp()
					]);
					$response->status(HttpCreated)->message('You return request was raised successfully.');
				} else {
					$response->status(HttpOkay)->message('Your return request could not be raised as this item is either non-returnable or the return period is over.');
				}
			}
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