<?php

namespace App\Http\Controllers\App\Seller;

use App\Enums\Seller\OrderStatus;
use App\Http\Controllers\AppController;
use App\Models\Auth\Seller;
use App\Models\SellerOrder;
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
		$seller->orders()->with('order')->each(function (SellerOrder $sellerOrder) use (&$sales) {
			$order = $sellerOrder->order;
			if ($order != null) {
				$sales += $order->total;
			}
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
			'grossRevenue' => $sales
		];
		return $response->status(HttpOkay)->message('Listing dashboard statistics.')->setValue('payload', $payload)->send();
	}

	protected function guard ()
	{
		return auth(self::SellerAPI);
	}
}