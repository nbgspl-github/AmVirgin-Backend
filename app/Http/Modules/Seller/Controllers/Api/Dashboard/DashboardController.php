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
		$sales = 0;
		$seller->orders()->each(function (SubOrder $sellerOrder) use (&$sales) {
			$sales += $sellerOrder->total;
		});
		$response = responseApp();
		$payload = [
			'products' => $seller->products()->count(),
			'sales' => $sales,
			'orders' => $seller->orders()->count('id'),
			'new' => $seller->orders()->where('status', Status::Placed)->count('id'),
			'delivered' => $seller->orders()->where('status', Status::Delivered)->count('id'),
			'cancelled' => $seller->orders()->where('status', Status::Cancelled)->count('id'),
			'pending' => $seller->orders()->where('status', Status::Pending)->count('id'),
			'grossRevenue' => $this->revenue($sales)
		];
		return $response->status(\Illuminate\Http\Response::HTTP_OK)->message('Listing dashboard statistics.')->setValue('payload', $payload)->send();
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