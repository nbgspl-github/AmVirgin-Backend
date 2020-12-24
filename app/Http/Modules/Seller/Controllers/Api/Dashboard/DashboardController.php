<?php

namespace App\Http\Modules\Seller\Controllers\Api\Dashboard;

use App\Library\Enums\Orders\Status;
use Illuminate\Http\JsonResponse;

class DashboardController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	/**
	 * Number of days to go back when filtering records.
	 */
	protected const DAYS_IN_PAST = 7;

	public function index (\App\Http\Modules\Seller\Requests\Dashboard\IndexRequest $request) : JsonResponse
	{
		$seller = $this->user();
		$now = \Illuminate\Support\Carbon::now()->endOfDay()->format('Y-m-d H:i:s');
		$past = \Illuminate\Support\Carbon::now()->subDays($request->days ?? self::DAYS_IN_PAST)->startOfDay()->format('Y-m-d H:i:s');
		$sales = $seller->orders()->whereBetween('created_at', [$past, $now])->sum('total');
		$payload = [
			'products' => $seller->products()->count(),
			'sales' => intval($sales),
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