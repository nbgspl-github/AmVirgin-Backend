<?php

namespace App\Http\Modules\Customer\Controllers\Api\Shop;

use App\Models\Product;
use App\Resources\Reviews\Customer\Products\ReviewResource;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class ProductRatingController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
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
		$response = responseApp();
		try {
			/**
			 * @var $product Product
			 */
			$product = Product::startQuery()->displayable()->key($id)->firstOrFail();
			$reviewCollection = ReviewResource::collection($product->reviews);
			$response->status($reviewCollection->count() > 0 ? \Illuminate\Http\Response::HTTP_OK : \Illuminate\Http\Response::HTTP_NO_CONTENT)->setValue('payload', $reviewCollection);
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message($exception->getMessage())->setValue('payload');
		} catch (\Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage())->setValue('payload');
		} finally {
			return $response->send();
		}
	}

	public function store ($id) : JsonResponse
	{
		$response = responseApp();
		/**
		 * @var $product Product
		 */
		$product = Product::startQuery()->displayable()->key($id)->firstOrFail();
		$validated = $this->requestValid(request(), $this->rules['store']);
		$reviewExists = $product->ratings()->where('customerId', $this->guard()->id())->exists();
		if (!$reviewExists) {
			$product->ratings()->create($validated);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Product ratings updated successfully.');
		} else {
			$response->status(\Illuminate\Http\Response::HTTP_CONFLICT)->message('Customer has already rated/reviewed the product.');
		}
		return $response->send();
	}
}