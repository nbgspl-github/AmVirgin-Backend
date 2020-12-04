<?php

namespace App\Http\Controllers\App\Seller\Orders;

use App\Http\Controllers\AppController;
use App\Models\Order;
use App\Resources\Orders\Seller\ListResource;
use Throwable;

class OrderController extends AppController
{
	public function index ()
	{
		$response = responseApp();
		try {
			$query = Order::query()->where('sellerId', $this->guard()->id());
			if (!empty(request('status'))) {
				$query->where('status', request('status'));
			}
			$orderCollection = $query->paginate();
			$total = $orderCollection->total();
			$totalRec = $orderCollection->total();
			$meta = [
				'pagination' => [
					'pages' => countRequiredPages($totalRec, request('per_page', 15)),
					'current_page' => request('page', 1),
					'items' => ['total' => $total, 'totalRec' => $totalRec, 'chunk' => request('per_page', 15)],
				],
			];
			$resourceCollection = ListResource::collection($orderCollection);
			$response->status(HttpOkay)->setValue('data', $resourceCollection)->setValue('meta', $meta);
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::SellerAPI);
	}
}