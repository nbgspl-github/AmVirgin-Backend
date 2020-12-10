<?php

namespace App\Http\Controllers\App\Seller;

use App\Classes\Arrays;
use App\Classes\Rule;
use App\Enums\Seller\OrderStatus;
use App\Http\Controllers\AppController;
use App\Models\Order;
use App\Models\ReviewRating;
use App\Models\SellerOrder;
use App\Resources\Orders\Seller\PaymentListResource;
use App\Resources\Orders\Seller\PreviousPaymentListResource;
use App\Traits\ValidatesRequest;
use BenSampo\Enum\Exceptions\InvalidEnumMemberException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Throwable;

class OrderController extends AppController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->rules = [
			'updateStatusBulk' => [
				'orderId' => ['bail', 'required', 'exists:orders,id'],
				'status' => ['bail', 'required', Rule::in(OrderStatus::getValues())],
				'cancellationReason' => ['bail', 'required_if:status,cancelled', 'string', 'min:2', 'max:500']
			]
		];
	}

	public function getPaymentsTransaction (): JsonResponse
	{
		$response = responseApp();

		$per_page = request()->get('per_page') ?? '';
		$page_no = request()->get('page') ?? '1';
		if (empty($per_page)) {
			$per_page = 10;
		}
		try {
			$orderC = SellerOrder::startQuery()->withRelations('order')->useAuth();
			if (!empty(request()->get('status'))) {
				$orderC->withWhere('status', request()->get('status'));
			}
			if (!empty(request()->get('query'))) {
				$orderC->search(request()->get('query'), 'orderNumber');
			}
			if (!empty(request()->get('from')) && !empty(request()->get('to'))) {
				$from = request()->get('from');
				$toDate = request()->get('to');
				$orderC->withWhereBetween('created_at', $from, $toDate);
			}
			$orderCollection = $orderC->paginate($per_page);

			$total = count($orderCollection);
			$totalRec = $orderCollection->total();
			$meta = [
				'pagination' => [
					'pages' => countRequiredPages($totalRec, $per_page),
					'current_page' => $page_no,
					'items' => ['total' => $total, 'totalRec' => $totalRec, 'chunk' => $per_page],
				],
			];
			$resourceCollection = PaymentListResource::collection($orderCollection);
			$response->status(HttpOkay)->message('Listing all Transaction for this seller.')->setValue('meta', $meta)->setValue('data', $resourceCollection);
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function getPreviousPayments (): JsonResponse
	{
		$response = responseApp();

		$per_page = request()->get('per_page') ?? '';
		$page_no = request()->get('page') ?? '1';
		if (empty($per_page)) {
			$per_page = 10;
		}
		try {
			$orderC = SellerOrder::startQuery()->withRelations('order')->withRelations('sellerBank:id,accountHolderName,accountHolderName,bankName,branch,ifsc')->useAuth();
			if (!empty(request()->get('status'))) {
				$orderC->withWhere('status', request()->get('status'));
			}
			if (!empty(request()->get('query'))) {
				$orderC->search(request()->get('query'), 'orderNumber');
			}
			if (!empty(request()->get('neftId'))) {
				$orderC->search(request()->get('neftId'), 'neftId');
			}
			if (!empty(request()->get('from')) && !empty(request()->get('to'))) {
				$from = request()->get('from');
				$toDate = request()->get('to');
				$orderC->withWhereBetween('created_at', $from, $toDate);
			}
			$orderCollection = $orderC->paginate($per_page);

			$total = count($orderCollection);
			$totalRec = $orderCollection->total();
			$meta = [
				'pagination' => [
					'pages' => countRequiredPages($totalRec, $per_page),
					'current_page' => $page_no,
					'items' => ['total' => $total, 'totalRec' => $totalRec, 'chunk' => $per_page],
				],
			];
			$resourceCollection = PreviousPaymentListResource::collection($orderCollection);
			$response->status(HttpOkay)->message('Listing all Previous Payment for this seller.')->setValue('meta', $meta)->setValue('data', $resourceCollection);
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function updateStatusBulk (): JsonResponse
	{
		$response = responseApp();
		try {
			$invalid = [];
			$validated = $this->requestValid(request(), $this->rules['updateStatusBulk']);
			$orderCollection = Order::query()->whereIn('id', $validated['orderId'])->get();
			$orderCollection->each(function (Order $order) use (&$invalid) {
				try {
					$transitions = OrderStatus::transitions(new OrderStatus($order->status));
					if (!empty(request('status')) && Arrays::contains($transitions, request('status'), true)) {
						$order->update([
							'status' => request('status'),
						]);
						if (request('status') == OrderStatus::Cancelled) {
							SellerOrder::query()->where('orderId', $order->getKey())->update(['status' => request('status'), 'cancellationReason' => request('cancellationReason')]);
						} else {
							SellerOrder::query()->where('orderId', $order->getKey())->update(['status' => request('status')]);
						}
					}
				} catch (InvalidEnumMemberException $exception) {
					$invalid[] = $order->getKey();
				}
			});
			$response->status(HttpOkay)->message('Order status updated successfully for batch.')->setValue('invalid', $invalid);
		} catch (Throwable $exception) {
			$response->status(HttpResourceNotFound)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function getRatingList (): JsonResponse
	{
		$response = responseApp();

		$per_page = request()->get('per_page') ?? '';
		$page_no = request()->get('page') ?? '1';
		if (empty($per_page)) {
			$per_page = 10;
		}
		try {
			// $ratingC = SellerOrder::startQuery()->withRelations('order')->withRelations('sellerBank:id,accountHolderName,accountHolderName,bankName,branch,ifsc')->useAuth();
			$today = Carbon::today();
			$ratingC = ReviewRating::with('customer')->where('sellerId', auth('seller-api')->id());
			if (!empty(request()->get('status'))) {
				$ratingC->withWhere('status', request()->get('status'));
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
			$orderCollection = $ratingC->get();

			// $total = count($orderCollection);
			// $totalRec = $orderCollection->total();
			$meta = [
				// 'pagination' => [
				// 	'pages' => countRequiredPages($totalRec, $per_page),
				// 	'current_page' => $page_no,
				// 	'items' => ['total' => $total, 'totalRec' => $totalRec, 'chunk' => $per_page],
				// ],
				'avg' => $orderCollection->avg('rate'),
			];
			// $resourceCollection = PreviousPaymentListResource::collection($orderCollection);
			$response->status(HttpOkay)->message('Listing all Rating With Avg Rating for this seller.')->setValue('meta', $meta)->setValue('data', $orderCollection);
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth('seller-api');
	}
}