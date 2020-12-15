<?php

namespace App\Http\Controllers\Api\Seller\Payments;

use App\Enums\Orders\Status;
use App\Http\Controllers\Api\ApiController;
use App\Models\OrderItem;
use App\Models\SubOrder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class PaymentController extends ApiController
{
	const PIVOT_DAY = 10;

	public function __construct ()
	{
		parent::__construct();
	}

	public function show () : JsonResponse
	{
		$response = responseApp();
		try {
			$overview = new \stdClass();
			$this->query()->each(function (SubOrder $order) use (&$overview) {

			});
		} catch (\Throwable $e) {
			$response->status(Response::HTTP_INTERNAL_SERVER_ERROR)->message($e->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function query () : HasMany
	{
		return $this->user()->orders()
			->whereIn('status', [Status::Delivered]);
	}

	/**
	 * Calculate and return sum of all applicable taxes.
	 * @param $amount
	 * @return float
	 */
	protected function taxes ($amount) : float
	{
		return (0.2 * $amount);
	}

	/**
	 * Calculate and return courier charges on this order.
	 * @param SubOrder $order
	 * @return float
	 */
	protected function courierChargeOverall (SubOrder $order) : float
	{
		return $this->shippingCost($order->items);
	}

	protected function shippingCost (OrderItem ...$items) : float
	{
		$cost = 0.0;
		foreach ($items as $item) {
			$cost += $item->product->shippingCost() ?? 0.0;
		}
		return $cost;
	}

	protected function makeOverview () : \stdClass
	{
		return (object)[
			'next' => [
				'date' => null,
				'prepaid' => 0,
				'postpaid' => 0,
				'total' => 0
			],
			'previous' => [
				'date' => null,
				'prepaid' => 0,
				'postpaid' => 0,
				'total' => 0
			],
			'total' => [
				'prepaid' => 0,
				'postpaid' => 0,
				'total' => 0
			]
		];
	}

	protected function previousMonth ($includeEnd = true) : \stdClass
	{
		/**
		 * If it's 10th (inclusive) of month, the effective duration is 26th of last to last month until 25th of last month.
		 */
		$today = Carbon::now()->day;
		if ($today <= self::PIVOT_DAY) {
			return (object)[
				'from' => Carbon::now()->setDay(26)->subMonths(2),
				'to' => null,
				'pivot' => null
			];
		} else {
			return (object)[
				'from' => null,
				'to' => null,
				'pivot' => null
			];
		}
	}

	protected function nextMonth ($includeEnd = true) : \stdClass
	{

	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}

	public function durations ()
	{

	}
}