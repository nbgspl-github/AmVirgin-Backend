<?php

namespace App\Http\Modules\Seller\Controllers\Api\Dashboard;

use App\Library\Enums\Orders\Status;
use App\Models\Auth\Seller;
use App\Models\Order\SubOrder;
use Illuminate\Http\JsonResponse;

class DashboardController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	public function index () : JsonResponse
	{
		/**
		 * @var $seller Seller
		 */
		$seller = $this->guard()->user();
		$now = \Illuminate\Support\Carbon::now()->endOfDay()->format('Y-m-d H:i:s');
		$past = \Illuminate\Support\Carbon::now()->subDays(7)->startOfDay()->format('Y-m-d H:i:s');
		$sales = 0;
		$seller->orders()->whereBetween('created_at', [$past, $now])->each(function (SubOrder $sellerOrder) use (&$sales) {
			$sales += $sellerOrder->total;
		});
		$payload = [
			'products' => $seller->products()->count(),
			'sales' => $sales,
			'orders' => $seller->orders()->whereBetween('created_at', [$past, $now])->count('id'),
			'new' => $seller->orders()->whereBetween('created_at', [$past, $now])->where('status', Status::Placed)->count('id'),
			'delivered' => $seller->orders()->whereBetween('created_at', [$past, $now])->where('status', Status::Delivered)->count('id'),
			'cancelled' => $seller->orders()->whereBetween('created_at', [$past, $now])->where('status', Status::Cancelled)->count('id'),
			'pending' => $seller->orders()->whereBetween('created_at', [$past, $now])->where('status', Status::Pending)->count('id'),
			'grossRevenue' => $this->revenue($sales),
			'range' => [
				'from' => $past,
				'to' => $now
			]
		];
		return responseApp()->prepare(
			$payload,
		);
	}

	protected function revenue ($sales) : float
	{
		$commission = $this->commission($sales);
		return $sales - $commission;
	}

	protected function commission ($sales) : float
	{
		return 0.33 * $sales;
	}
}