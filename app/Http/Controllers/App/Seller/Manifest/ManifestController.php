<?php

namespace App\Http\Controllers\App\Seller\Manifest;

use App\Enums\Seller\OrderStatus;
use App\Http\Controllers\AppController;
use App\Models\SubOrder;
use App\Resources\Manifest\Seller\ListResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class ManifestController extends AppController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function show (): JsonResponse
	{
		$response = responseApp();
		try {
			if (is_array(request('orderId'))) {
				$subOrderCollection = SubOrder::startQuery()->whereIn('id', request('orderId', []))->useAuth()->get();
				if (request('update', 0) == 1) {
					$subOrderCollection->each(function (SubOrder $subOrder) {
						$subOrder->update([
							'status' => OrderStatus::PendingDispatch
						]);
					});
				}
				$resourceCollection = ListResource::collection($subOrderCollection);
				$response->status($resourceCollection->count() > 0 ? HttpOkay : HttpNoContent)->message('Listing all details for order keys.')->setValue('payload', $resourceCollection);
			} else {
				$subOrder = SubOrder::startQuery()->key(request('orderId'))->useAuth()->first();
				if (request('update', 0) == 1 && $subOrder != null) {
					$subOrder->update([
						'status' => OrderStatus::PendingDispatch
					]);
				}
				$resource = new ListResource($subOrder);
				$response->status(HttpOkay)->message('Listing all details for order.')->setValue('payload', $resource);
			}
		} catch (Throwable $e) {
			$response->status(Response::HTTP_INTERNAL_SERVER_ERROR)->message($e->getMessage());
		} finally {
			return $response->send();
		}
	}


	protected function guard ()
	{
		return auth(self::SellerAPI);
	}
}