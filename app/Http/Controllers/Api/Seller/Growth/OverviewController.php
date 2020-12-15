<?php

namespace App\Http\Controllers\Api\Seller\Growth;

use App\Enums\Seller\OrderStatus;
use App\Http\Controllers\Api\ApiController;
use App\Models\ReviewRating;
use App\Models\SellerOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Throwable;

class OverviewController extends ApiController
{
	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->rules = [
			'show' => [
				'chunk' => ['bail', 'nullable', 'numeric', 'min:1', 'max:1000'],
				'page' => ['bail', 'nullable', 'numeric', 'min:1'],
			]
		];
	}

	public function show (): JsonResponse
	{
		$response = responseApp();
		try {
			$today = Carbon::today();
			$ratingC = ReviewRating::with('customer')->where('sellerId', auth('seller-api')->id());
			if (!empty(request()->get('status'))) {
				$ratingC->where('status', request()->get('status'));
			}
			if (!empty(request()->get('query'))) {
				$keywords = request()->get('query');
				$ratingC->where('orderNumber', 'LIKE', "%{$keywords}%");
				$ratingC->orWhere('commentMsg', 'LIKE', "%{$keywords}%");
				$ratingC->orWhere('rate', 'LIKE', "%{$keywords}%");
			}
			if (!empty(request('days'))) {
				$ratingC->where('created_at', '>=', $today->subDays(request('days')));
			}
			if (!empty(request()->get('from')) && !empty(request()->get('to'))) {
				$from = request()->get('from');
				$toDate = request()->get('to');
				$ratingC->whereBetween('created_at', [$from, $toDate]);
			}
			$orderCollectionRating = $ratingC->get();
			$today = Carbon::today();
			$current = Carbon::now()->timestamp;
			$orderC = SellerOrder::startQuery()->useAuth()->withRelations('order');
			if (!empty(request('days'))) {
				$orderC->useWhere('created_at', '>=', $today->subDays(request('days')));
			}
			$orderCollection = $orderC->status((new OrderStatus(OrderStatus::Delivered)))->get();
			$dataSet = array();
			$dataSet['salesInUnit'] = count($orderCollection);
			$salesInRupee = 0;
			foreach ($orderCollection as $key => $value) {
				$salesInRupee += $value->order->total;
			}
			$dataSet['salesInRupees'] = $salesInRupee;
			$average = $orderCollectionRating->avg('rate') ?? "0";
			$meta = [
				'averageRating' => $average,
				'customerReturns' => mt_rand(10, 100),
				'sales' => $dataSet
			];
			$response->status(HttpOkay)->message('Listing all rating and performance stats.')->setValue('payload', $meta);
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}
