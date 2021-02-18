<?php

namespace App\Http\Modules\Customer\Controllers\Api\Cart;

use App\Classes\Cart\CartItem;
use App\Exceptions\ValidationException;
use App\Library\Enums\Cart\Status;
use App\Models\Cart\Cart;
use App\Models\CustomerWishlist;
use App\Models\Product;
use App\Resources\Products\Customer\CatalogListResource;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class WishlistController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_CUSTOMER);
		$this->rules = [
			'store' => [
				'productId' => ['bail', 'required', Product::exists()],
			],
			'moveToCart' => [
				'sessionId' => ['bail', 'required', \App\Models\Cart\Session::exists('sessionId')],
			],
		];
	}

	public function index () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			CatalogListResource::collection(
				$this->customer()->wishListProducts
			), \Illuminate\Http\Response::HTTP_OK, 'Listing wishlist items.', 'data'
		);
	}

	public function store ($productId) : \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		$validated = null;
		try {
			CustomerWishlist::query()->where([
				['customerId', $this->customer()->id],
				['productId', $productId],
			])->firstOrFail();
			$response->status(\Illuminate\Http\Response::HTTP_CONFLICT)->message('Item already exists in wishlist.');
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getError());
		} catch (ModelNotFoundException $exception) {
			try {
				Product::query()->findOrFail($productId);
				CustomerWishlist::query()->create([
					'customerId' => $this->customer()->id,
					'productId' => $productId,
				]);
				$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Item added to wishlist.');
			} catch (ModelNotFoundException $exception) {
				$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find product for that key.');
			}
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function delete ($productId) : \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		try {
			$wishListItem = CustomerWishlist::query()->where([
				['customerId', $this->customer()->id],
				['productId', $productId],
			])->firstOrFail();
			$wishListItem->delete();
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Item removed from wishlist.');
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Item not found in wishlist.');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function moveToCart ($productId) : \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		$validated = null;
		$cart = null;
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['moveToCart']);
			$wishlistItem = CustomerWishlist::query()->where([
				['customerId', $this->customer()->id],
				['productId', $productId],
			])->firstOrFail();
			try {
				$cart = Cart::retrieveThrows($validated->sessionId);
				$cartItem = new CartItem($cart, $productId);
				if (!$cart->contains($cartItem)) {
					$wishlistItem->delete();
					$cart->addItem($cartItem);
					$cart->save();
					$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Item moved to cart.');
				} else {
					$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Cart already contains the item you specified.');
				}
			} catch (ModelNotFoundException $exception) {
				\App\Models\Cart\Cart::query()->create([
					'sessionId' => $validated->sessionId,
					'status' => Status::Pending,
				]);
				$cart = Cart::retrieveThrows($validated->sessionId);
				$cartItem = new CartItem($cart, $productId);
				$wishlistItem->delete();
				$cart->addItem($cartItem);
				$cart->save();
				$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Item moved to cart.');
			}
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Item was not found in the wishlist.');
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}