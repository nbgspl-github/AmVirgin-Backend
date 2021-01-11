<?php

namespace App\Http\Modules\Seller\Controllers\Api\Payments;

use App\Http\Modules\Seller\Requests\Payment\IndexRequest;
use App\Library\Enums\Orders\Status;
use App\Models\Order\SubOrder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;

class PaymentController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER);
	}

	public function index (IndexRequest $request) : JsonResponse
	{
		$orders = $this->query($request)->get();
		$orders->transform(function (SubOrder $order) {
			return (object)[
				'key' => $order->id,
				'date' => $order->created_at->format(\App\Library\Utils\Extensions\Time::MYSQL_FORMAT),
				'description' => $this->description($order),
				'quantity' => $order->quantity,
				'sales' => $order->total,
				'sellingFee' => $order->sellingFee(),
				'courierCharges' => $order->courierCharge(),
				'total' => $order->grossTotal()
			];
		});

		return responseApp()->prepare(
			$orders
		);
	}

	protected function query (IndexRequest $request) : HasMany
	{
		$query = $this->user()->orders()
			->whereIn('status', [Status::Delivered])
			->latest('created_at');
		if ($request->has('key'))
			$query = $query->whereKey($request->key);
		if ($request->has(['start', 'end']))
			$query = $query->whereBetween('created_at', [$request->start, $request->end]);
		return $query;
	}

	protected function description (SubOrder $order) : string
	{
		$products = $order->products->transform(function (\App\Models\Product $product) {
			return "{$product->pivot->quantity} x {$product->name}";
		});
		return \App\Library\Utils\Extensions\Str::join(',', $products->toArray());
	}
}