<?php

namespace App\Http\Controllers\Api\Customer\Shop;

use App\Http\Controllers\Api\ApiController;
use App\Models\Product;
use App\Resources\Reviews\Customer\Products\ReviewResource;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class ProductRatingController extends ApiController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->rules = [
			'store' => [
				'comment' => 'bail|nullable|string|max:255',
				'rating' => 'bail|required|numeric|min:0.11|max:5.00'
			]
		];
	}

	public function show ($id) : JsonResponse
	{
		$response = $this->responseApp();
		try {
			/**
			 * @var $product Product
			 */
			$product = Product::startQuery()->displayable()->key($id)->firstOrFail();
			$reviewCollection = ReviewResource::collection($product->reviews);
			$response->status($reviewCollection->count() > 0 ? HttpOkay : HttpNoContent)->setValue('payload', $reviewCollection);
		} catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message($exception->getMessage())->setValue('payload');
		} catch (\Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage())->setValue('payload');
		} finally {
			return $response->send();
		}
	}

	public function store ($id) : JsonResponse
	{
		$response = $this->responseApp();
		/**
		 * @var $product Product
		 */
		$product = Product::startQuery()->displayable()->key($id)->firstOrFail();
		$validated = $this->requestValid(request(), $this->rules['store']);
		$reviewExists = $product->ratings()->where('customerId', $this->guard()->id())->exists();
		if (!$reviewExists) {
			$product->ratings()->create($validated);
			$response->status(HttpOkay)->message('Product ratings updated successfully.');
		} else {
			$response->status(HttpResourceAlreadyExists)->message('Customer has already rated/reviewed the product.');
		}
		return $response->send();
	}

	protected function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}