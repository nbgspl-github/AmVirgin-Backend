<?php

namespace App\Http\Controllers\App\Customer\Shop;

use App\Exceptions\ValidationException;
use App\Http\Controllers\AppController;
use App\Models\Product;
use App\Resources\Reviews\Customer\Products\ReviewResource;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class ProductRatingController extends AppController
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

	public function show ($id): JsonResponse
	{
		$response = $this->response();
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

	public function store ($id): JsonResponse
	{
		$response = $this->response();
		try {
			/**
			 * @var $product Product
			 */
			$product = Product::startQuery()->displayable()->key($id)->firstOrFail();
			$validated = $this->requestValid(request(), $this->rules['store']);
			$reviewExists = $product->reviews()->where('customerId', $this->guard()->id())->exists();
			if (!$reviewExists) {
				$product->reviews()->create($validated);
				$response->status(HttpOkay)->message('Product ratings updated successfully.');
			} else {
				$response->status(HttpResourceAlreadyExists)->message('Customer has already rated/reviewed the product.');
			}
		} catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message($exception->getMessage());
		} catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getError());
		} catch (\Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::CustomerAPI);
	}
}