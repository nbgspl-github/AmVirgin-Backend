<?php

namespace App\Http\Controllers\App\Seller;

use App\Enums\Seller\OrderStatus;
use App\Http\Controllers\AppController;
use App\Models\Auth\Seller;
use App\Models\SubOrder;
use Illuminate\Http\JsonResponse;

class DashboardController extends AppController
{
	public function index (): JsonResponse
	{
		/**
		 * @var $seller Seller
		 */
		$seller = $this->guard()->user();
		$sales = 0;
		$seller->orders()->each(function (SubOrder $sellerOrder) use (&$sales) {
			$sales += $sellerOrder->total;
		});
		$response = $this->response();
		$payload = [
			'products' => $seller->products()->count(),
			'sales' => $sales,
			'orders' => $seller->orders()->count('id'),
			'new' => $seller->orders()->where('status', OrderStatus::Placed)->count('id'),
			'delivered' => $seller->orders()->where('status', OrderStatus::Delivered)->count('id'),
			'cancelled' => $seller->orders()->where('status', OrderStatus::Cancelled)->count('id'),
			'pending' => $seller->orders()->where('status', OrderStatus::Pending)->count('id'),
			'grossRevenue' => $this->revenue($sales)
		];
		return $response->status(HttpOkay)->message('Listing dashboard statistics.')->setValue('payload', $payload)->send();
	}

	protected function revenue ($sales): float
	{
		$commission = $this->commission($sales);
		return $sales - $commission;
	}

	protected function commission ($sales): float
	{
		return 0.33 * $sales;
	}

	protected function guard ()
	{
		return auth(self::SellerAPI);
	}
}