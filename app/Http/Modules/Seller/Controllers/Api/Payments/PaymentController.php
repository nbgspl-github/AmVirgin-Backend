<?php

namespace App\Http\Modules\Seller\Controllers\Api\Payments;

use App\Library\Enums\Orders\Status;
use App\Models\Order\SubOrder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class PaymentController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	const PIVOT_DAY = 26;

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER);
	}

	public function index () : JsonResponse
	{
		$orders = $this->query()->get();
		$orders->transform(function (SubOrder $order) {
			return (object)[
				'key' => $order->id,
				'date' => $order->created_at->format(\App\Library\Utils\Extensions\Time::MYSQL_FORMAT),
				'description' => null,
				'quantity' => $order->quantity,
				'sales' => $order->total,
				'sellingFee' => $this->sellingFee($order->total),
				'courierCharges' => $this->courierChargeOverall($order),
				'total' => $this->grossTotal($order)
			];
		});

		return responseApp()->prepare(
			$orders
		);
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
	protected function sellingFee ($amount) : float
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

	protected function shippingCost (\Illuminate\Support\Collection $items) : float
	{
		$cost = 0.0;
		foreach ($items as $item) {
			$cost += $item->product->shippingCost() ?? 0.0;
		}
		return $cost;
	}

	protected function grossTotal (SubOrder $order) : float
	{
		$sellingFee = $this->sellingFee($order->total);
		$shippingCost = $this->courierChargeOverall($order);
		return (
			$order->total - ($sellingFee + $shippingCost)
		);
	}

	protected function lastPaymentDate () : Carbon
	{
		/**
		 * Unless there's a last payment date available,
		 * we consider the starting period on the day seller
		 * registered on our portal
		 */
		return $this->seller()->created_at;
	}
}