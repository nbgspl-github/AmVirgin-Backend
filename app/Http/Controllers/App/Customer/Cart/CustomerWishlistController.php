<?php

namespace App\Http\Controllers\App\Customer\Cart;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Interfaces\Tables;
use App\Models\CustomerWishlist;
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
		];
	}

	public function index() {
		$wishList = CustomerWishlist::where('customerId', $this->guard()->id())->get();
		$wishList->transform(function (CustomerWishlist $item) {
			return $item->productId;
		});
		return responseApp()->status(HttpOkay)->setValue('data', $wishList)->message(function () use ($wishList) {
			return sprintf('Found %d items on the wishlist.', $wishList->count());
		})->send();
	}

	public function store() {
		$response = responseApp();
		$validated = null;
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['store']);
			CustomerWishlist::where([
				['customerId', $this->guard()->id()],
				['productId', $validated->productId],
			])->firstOrFail();
			$response->status(HttpResourceAlreadyExists)->message('Item already exists in wishlist.');
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getError());
		}
		catch (ModelNotFoundException $exception) {
			CustomerWishlist::create([
				'customerId' => $this->guard()->id(),
				'productId' => $validated->productId,
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
			$response->status(HttpResourceAlreadyExists)->message('Item removed from wishlist.');
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpOkay)->message('Item not found in wishlist.');
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