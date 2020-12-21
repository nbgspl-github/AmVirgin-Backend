<?php

namespace App\Http\Controllers\API\Customer\Cart;

use App\Classes\Cart\CartItem;
use App\Exceptions\ValidationException;
use App\Http\Controllers\AppController;
use App\Library\Enums\Cart\Status;
use App\Library\Enums\Common\Tables;
use App\Models\Cart;
use App\Models\CustomerWishlist;
use App\Models\Product;
use App\Resources\Products\Customer\CatalogListResource;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Throwable;

class CustomerWishlistController extends AppController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->rules = [
			'store' => [
				'productId' => ['bail', 'required', Rule::exists(Tables::Products, 'id')],
			],
			'moveToCart' => [
				'sessionId' => ['bail', 'required', Rule::exists(Tables::CartSessions, 'sessionId')],
			],
		];
	}

	public function index ()
	{
		$wishList = CustomerWishlist::where('customerId', $this->guard()->id())->get();
		$wishList->transform(function (CustomerWishlist $item) {
			return new CatalogListResource(Product::find($item->productId));
		});
		return responseApp()->status(\Illuminate\Http\Response::HTTP_OK)->setValue('data', $wishList)->message(function () use ($wishList) {
			return sprintf('Found %d items in the wishlist.', $wishList->count());
		})->send();
	}

	public function store ($productId)
	{
		$response = responseApp();
		$validated = null;
		try {
			CustomerWishlist::where([
				['customerId', $this->guard()->id()],
				['productId', $productId],
			])->firstOrFail();
			$response->status(\Illuminate\Http\Response::HTTP_CONFLICT)->message('Item already exists in wishlist.');
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getError());
		} catch (ModelNotFoundException $exception) {
			try {
				Product::findOrFail($productId);
				CustomerWishlist::create([
					'customerId' => $this->guard()->id(),
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

	public function delete ($productId)
	{
		$response = responseApp();
		try {
			$wishListItem = CustomerWishlist::where([
				['customerId', $this->guard()->id()],
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

	public function moveToCart ($productId)
	{
		$response = responseApp();
		$validated = null;
		$cart = null;
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['moveToCart']);
			$wishlistItem = CustomerWishlist::where([
				['customerId', $this->guard()->id()],
				['productId', $productId],
			])->firstOrFail();
			try {
				$cart = Cart::findOrFail($validated->sessionId);
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
				\App\Models\Cart::create([
					'sessionId' => $validated->sessionId,
					'status' => Status::Pending,
				]);
				$cart = Cart::findOrFail($validated->sessionId);
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

	protected function guard ()
	{
		return auth('customer-api');
	}
}