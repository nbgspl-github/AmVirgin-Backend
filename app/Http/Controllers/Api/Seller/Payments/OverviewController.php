<?php

namespace App\Http\Controllers\Api\Seller\Payments;

use App\Enums\Orders\Status;
use App\Enums\Seller\OrderStatus;
use App\Http\Controllers\Api\ApiController;
use App\Models\SellerOrder;
use App\Models\SubOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class OverviewController extends ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function show (): JsonResponse
	{
		$response = responseApp();
		try {
			$current = Carbon::now()->timestamp;
			$payload = [
				'next' => [
					'date' => date('j F', $current),
					'postpaid' => 0.0,
					'prepaid' => 0.0,
					'total' => 0.0
				],
				'previous' => [
					'date' => date('j F', $current),
					'postpaid' => 0.0,
					'prepaid' => 0.0,
					'total' => 0.0
				],
				'total' => [
					'postpaid' => 0.0,
					'prepaid' => 0.0,
					'total' => 0.0
				]
			];
			$previous = new Collection();
			$orderCollection = SubOrder::startQuery()->useAuth()->withinCurrentMonth()->status((new Status(Status::Delivered)))->get();
			$orderCollection->each(function (SubOrder $subOrder) use ($current, &$payload, $previous) {
				$order = $subOrder;
				$payload['total']['postpaid'] += $order->total;
				$payload['total']['prepaid'] += $order->total;
				if (strtotime($order->created_at) < $current) {
					$previous->push(strtotime($order->created_at));
					$payload['previous']['postpaid'] += $order->total;
					$payload['previous']['prepaid'] += $order->total;
				} else {
					$payload['next']['postpaid'] += $order->total;
					$payload['next']['prepaid'] += $order->total;
				}
			});
			$last = $previous->sort()->last();
			$payload['previous']['date'] = !empty($last) ? date("F j, Y, g:i a", $last) : '<N/A>';
			$response->status(HttpOkay)->message('Payment overview details retrieved successfully.')->setValue('payload', $payload);
		} catch (\Throwable $exception) {
			$response->status(HttpOkay)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function totalSales (): JsonResponse
	{
		$response = responseApp();
		try {
			$today = Carbon::today();
			$current = Carbon::now()->timestamp;
			$orderC = SellerOrder::startQuery()->useAuth()->withRelations('order');
			if (!empty(request('days'))) {
				$orderC->useWhere('created_at', '>=', $today->subDays(request('days')));
			}
			$orderCollection = $orderC->status((new OrderStatus(OrderStatus::Delivered)))->get();
			$datSet = array();
			if (!empty(count($orderCollection))) {
				$i = 0;
				$datSet['salesInUnit'] = count($orderCollection);
				$salesInRupee = 0;
				foreach ($orderCollection as $key => $value) {
					$salesInRupee += $value->order->total;
				}
				$datSet['salesInRupees'] = $salesInRupee;
			}
			$response->status(HttpOkay)->message('Sales in last ' . request('days') . ' days retrieved successfully.')->setValue('payload', $datSet);
		} catch (\Throwable $exception) {
			$response->status(HttpOkay)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}