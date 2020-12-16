<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\BaseController;
use App\Library\Enums\Orders\Status;
use App\Models\Auth\Customer;
use App\Models\Auth\Seller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Video;

class HomeController extends BaseController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware('auth:admin');
	}

	public function index ()
	{
		$videoCount = Video::startQuery()->displayable()->count();
		$seriesCount = Video::startQuery()->displayable()->series()->count();
		$movieCount = Video::startQuery()->displayable()->movie()->count();
		$newVideoCount = Video::startQuery()->displayable()->isNew()->count();
		$newSeriesCount = Video::startQuery()->displayable()->series()->isNew()->count();
		$newMovieCount = Video::startQuery()->displayable()->movie()->isNew()->count();
		$customerCount = Customer::query()->count();
		$sellerCount = Seller::query()->count();
		$productCount = Product::startQuery()->displayable()->count();
		$orderCount = Order::query()->count();
		$pendingOrders = Order::query()->where('status', Status::Pending)->count();
		$cancelledOrders = Order::query()->where('status', Status::Cancelled)->count();
		$deliveredOrders = Order::query()->where('status', Status::Delivered)->count();
		$payload = [
			'video' => $videoCount,
			'series' => $seriesCount,
			'movie' => $movieCount,
			'newVideo' => $newVideoCount,
			'newSeries' => $newSeriesCount,
			'newMovie' => $newMovieCount,
			'customers' => $customerCount,
			'sellers' => $sellerCount,
			'products' => $productCount,
			'orders' => $orderCount,
			'pendingOrders' => $pendingOrders,
			'cancelledOrders' => $cancelledOrders,
			'deliveredOrders' => $deliveredOrders,
		];
		return view('admin.home.dashboard')->with('stats', (object)$payload);
	}
}