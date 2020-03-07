<?php

namespace App\Http\Controllers\App\Seller;

use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\SellerOrder;
use App\Traits\ValidatesRequest;
use Throwable;

class OrdersController extends ExtendedResourceController {
	use ValidatesRequest;

	protected array $rules;

	public function __construct() {
		parent::__construct();
		$this->rules = [

		];
	}

	public function index() {
		$response = responseApp();
		try {
			$orders = SellerOrder::where([
				['sellerId', $this->guard()->id()],
			])->get();
			$orders->transform(function (SellerOrder $sellerOrder) {
				return [
					'orderId' => $sellerOrder->orderId(),
					'orderNumber' => $sellerOrder->orderNumber(),
					'customerId' => $sellerOrder->customerId(),
					'items' => $sellerOrder->items,
				];
			});
			$response->status(HttpOkay)->message('Listing all orders for this seller.')->setValue('data', $orders);
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	protected function guard() {
		return auth('seller-api');
	}
}