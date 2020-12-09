<?php

namespace App\Http\Controllers\App\Seller\Manifest;

use App\Enums\Seller\OrderStatus;
use App\Http\Controllers\AppController;
use App\Models\SellerOrder;
use App\Resources\Manifest\Seller\ListResource;
use Throwable;

class ManifestController extends AppController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function show ()
	{
		$response = responseApp();
		try {
			if (is_array(request('orderId'))) {
				$sellerOrderCollection = SellerOrder::query()->whereIn('id', request('orderId'))->where('sellerId', $this->userId())->get();
				if (request('update', 0) == 1) {
					$sellerOrderCollection->each(function (SellerOrder $sellerOrder) {
						if ($sellerOrder->order()->exists()) {
							$sellerOrder->update([
								'status' => OrderStatus::PendingDispatch
							]);
							$sellerOrder->order->update([
								'status' => OrderStatus::PendingDispatch
							]);
						}
					});
				}
				$resourceCollection = ListResource::collection($sellerOrderCollection);
				$response->status($resourceCollection->count() > 0 ? HttpOkay : HttpNoContent)->message('Listing all details for order keys.')->setValue('payload', $resourceCollection);
			} else {
				$sellerOrder = SellerOrder::query()->whereKey(request('orderId'))->where('sellerId', $this->userId())->first();
				if (request('update', 0) == 1) {
					if ($sellerOrder->order()->exists()) {
						$sellerOrder->update([
							'status' => OrderStatus::PendingDispatch
						]);
						$sellerOrder->order->update([
							'status' => OrderStatus::PendingDispatch
						]);
					}
				}
				$resource = new ListResource($sellerOrder);
				$response->status(HttpOkay)->message('Listing all details for order.')->setValue('payload', $resource);
			}
		} catch (Throwable $exception) {
			$response->status(HttpResourceNotFound)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}


	protected function guard ()
	{
		return auth(self::SellerAPI);
	}
}