<?php

namespace App\Http\Controllers\Api\Seller\Growth;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\ApiController;
use App\Library\Enums\Orders\Status;
use App\Models\ProductRating;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class OverviewController extends ApiController
{
	use ValidatesRequest;

	protected array $rules;

	protected const DAYS_IN_PAST = 7;

	public function __construct ()
	{
		parent::__construct();
		$this->rules = [
			'show' => [
				'days' => ['bail', 'sometimes', 'numeric', 'gte:1']
			]
		];
	}

	/**
	 * @return JsonResponse
	 * @throws ValidationException
	 */
	public function show () : JsonResponse
	{
		$response = responseApp();
		$validated = $this->requestValid(request(), $this->rules['show']);
		$days = $validated['days'] ?? self::DAYS_IN_PAST;
		$range = [
			Carbon::now()->subDays($days)->startOfDay()->format('Y-m-d H:i:s'),
			Carbon::now()->endOfDay()->format('Y-m-d H:i:s'),
		];
		$payload = [
			'sales' => [
				'amount' => $this->seller()->orders()->where('status', Status::Delivered)->whereBetween('created_at', $range)->sum('total'),
				'units' => $this->seller()->orders()->where('status', Status::Delivered)->whereBetween('created_at', $range)->sum('quantity')
			],
			'product' => $this->bestRatedProduct($this->seller()->productRatings()->whereBetween('created_at', $range)->get()),
			'customerReturns' => $this->seller()->returns()->whereBetween('created_at', $range)->count()
		];
		$response->status(Response::HTTP_OK)->message("Growth details calculated for {$days} days!")->payload($payload);
		return $response->send();
	}

	protected function bestRatedProduct (Collection $collection) : array
	{
		$collection = $collection->groupBy(function (ProductRating $rating) {
			return $rating->product_id;
		});
		$collection->transform(function (Collection $collection, int $productId) {
			$count = $collection->count();
			$total = $collection->sum(function (ProductRating $rating) {
				return $rating->stars ?? 0;
			});
			$average = $total / (float)$count;
			return [
				'key' => $productId,
				'average' => $average
			];
		});
		return [
			'item' => null,
			'rating' => $collection
		];
	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}
