<?php

namespace App\Http\Controllers\App\Customer\Cart;

use App\Classes\Cart\CartItem;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Interfaces\Tables;
use App\Models\Cart;
use App\Models\CustomerWishlist;
use App\Models\Product;
use App\Resources\Products\Customer\ProductResource;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Throwable;

class CustomerWishlistController extends ExtendedResourceController {
	use ValidatesRequest;
	protected array $rules;

	public function __construct() {
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

	public function index() {
		$wishList = CustomerWishlist::where('customerId', $this->guard()->id())->get();
		$wishList->transform(function (CustomerWishlist $item) {
			return new ProductResource(Product::retrieve($item->productId));
		});
		return responseApp()->status(HttpOkay)->setValue('data', $wishList)->message(function () use ($wishList) {
			return sprintf('Found %d items in the wishlist.', $wishList->count());
		})->send();
	}

	public function store($productId) {
		$response = responseApp();
		$validated = null;
		try {
			CustomerWishlist::where([
				['customerId', $this->guard()->id()],
				['productId', $productId],
			])->firstOrFail();
			$response->status(HttpResourceAlreadyExists)->message('Item already exists in wishlist.');
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getError());
		}
		catch (ModelNotFoundException $exception) {
			CustomerWishlist::create([
				'customerId' => $this->guard()->id(),
				'productId' => $productId,
			]);
			$response->status(HttpOkay)->message('Item added to wishlist.');
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function delete($productId) {
		$response = responseApp();
		try {
			$wishListItem = CustomerWishlist::where([
				['customerId', $this->guard()->id()],
				['productId', $productId],
			])->firstOrFail();
			$wishListItem->delete();
			$response->status(HttpOkay)->message('Item removed from wishlist.');
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Item not found in wishlist.');
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function moveToCart($productId) {
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
				$cart = Cart::retrieveThrows($validated->sessionId);
				$cartItem = new CartItem($cart, $productId);
				if (!$cart->contains($cartItem)) {
					$wishlistItem->delete();
					$cart->addItem($cartItem);
					$cart->save();
					$response->status(HttpOkay)->message('Item moved to cart.');
				}
				else {
					$response->status(HttpResourceNotFound)->message('Cart already contains the item you specified.');
				}
			}
			catch (ModelNotFoundException $exception) {
				$response->status(HttpOkay)->message('No cart was found for that session.');
			}
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpOkay)->message('Item was not found in the wishlist.');
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getError());
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	protected function guard() {
		return auth('customer-api');
	}
}