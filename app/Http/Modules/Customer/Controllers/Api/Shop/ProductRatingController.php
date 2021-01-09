<?php

namespace App\Http\Modules\Customer\Controllers\Api\Shop;

use App\Http\Modules\Customer\Requests\Shop\Product\Ratings\StoreRequest;
use App\Models\Product;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;

class ProductRatingController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_CUSTOMER)->except('show');
	}

	public function show (Product $product) : JsonResponse
	{
		return responseApp()->prepare([
			\App\Http\Modules\Customer\Resources\Entertainment\Product\Review\ReviewResource::collection(
				$product->ratings()->latest()->paginate($this->paginationChunk())
			)->response()->getData()
		]);
	}

	public function store (StoreRequest $request, Product $product, \App\Models\Order\Order $order) : JsonResponse
	{
		if (!$product->ratingsBy($this->customer())->exists()) {
			$validated = $request->validated();
			$validated = array_merge($validated, [
				'seller_id' => $product->sellerId,
				'certified' => $order->items()->where('productId', $product->id)->exists()
			]);
			/**
			 * @var $review \App\Models\ProductRating
			 */
			$review = $product->addRatingBy($this->customer(), $validated);
			\App\Library\Utils\Extensions\Arrays::each($validated['image'] ?? [], function (\Illuminate\Http\UploadedFile $file) use (&$review) {
				$review->images()->create([
					'file' => $file
				]);
			});
			return responseApp()->prepare(
				new \App\Http\Modules\Customer\Resources\Entertainment\Product\Review\ReviewResource($review),
				\Illuminate\Http\Response::HTTP_OK, 'Product ratings updated successfully.'
			);
		} else {
			return responseApp()->prepare(
				null, \Illuminate\Http\Response::HTTP_CONFLICT, 'Customer has already rated/reviewed the product.'
			);
		}
	}

	protected function text (float $stars) : string
	{
		if ($stars <= 1)
			return "Worst";
		elseif ($stars <= 2)
			return "Bad";
		elseif ($stars <= 3)
			return "Average";
		elseif ($stars <= 4)
			return "Good";
		else
			return "Excellent";
	}
}