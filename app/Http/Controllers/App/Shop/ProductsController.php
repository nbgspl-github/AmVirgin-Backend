<?php

namespace App\Http\Controllers\App\Shop;

use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\ProductImage;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ProductsController extends BaseController{
	use ValidatesRequest;
	use FluentResponse;

	public function __construct(){
		parent::__construct();
//		$this->ruleSet->load('rules.shop.product');
	}

	public function index(Request $request){
		$response = null;
		try {
			$data = null;
			$validator = Validator::make($request->all(), [
				'offset' => 'required',
				'limit' => 'required',
			]);
			if ($validator->fails()) {
				return response()->json(['response' => $validator->errors()], 500);

			}
			$offset = $request->input('offset');
			$limit = $request->input('limit');
			$sort = ["field" => "id", "key" => "desc"];
			if ($request->has('sort')) {
				if ($request->sort == "Oldest") {
					$sort = ["field" => "id", "key" => "asc"];
				}
				if ($request->sort == "Cheapest") {
					$sort = ["field" => "originalPrice", "key" => "asc"];
				}
				if ($request->sort == "Highest") {
					$sort = ["field" => "originalPrice", "key" => "desc"];
				}
			}
			//if($categoryid)
			$Getproducts = Product::orderBy($sort['field'], $sort['key'])->offset($offset)->limit($limit)->get();

			if (count($Getproducts) == 0) {
				$response = $this->failed()->status(HttpResourceNotFound)->message(__(' Products not found'));;

			}
			else {
				foreach ($Getproducts as $pdata) {
					$productsimage = ProductImage::where('productId', $pdata['id'])->select('productId', 'path', 'tag')->get();
					$productsimage->transform(function (ProductImage $item){
						return Storage::disk('secured')->url($item->path);
					});
					$success[] = [
						'id' => $pdata['id'],
						'name' => $pdata['name'],
						'slug' => $pdata['slug'],
						'categoryId' => $pdata['categoryId'],
						'sellerId' => $pdata['sellerId'],
						'productType' => $pdata['productType'],
						'productMode' => $pdata['productMode'],
						'listingType' => $pdata['listingType'],
						'originalPrice' => $pdata['originalPrice'],
						'offerValue' => $pdata['offerValue'],
						'offerType' => $pdata['offerType'],
						'currency' => $pdata['currency'],
						'taxRate' => $pdata['taxRate'],
						'countryId' => $pdata['countryId'],
						'stateId' => $pdata['stateId'],
						'cityId' => $pdata['cityId'],
						'zipCode' => $pdata['zipCode'],
						'address' => $pdata['address'],
						'status' => $pdata['status'],
						'promoted' => $pdata['promoted'],
						'promotionStart' => $pdata['promotionStart'],
						'promotionEnd' => $pdata['promotionEnd'],
						'visibility' => $pdata['visibility'],
						'rating' => $pdata['rating'],
						'hits' => $pdata['hits'],
						'stock' => $pdata['stock'],
						'shippingCostType' => $pdata['shippingCostType'],
						'shippingCost' => $pdata['shippingCost'],
						'soldOut' => $pdata['soldOut'],
						'deleted' => $pdata['deleted'],
						'draft' => $pdata['draft'],
						'shortDescription' => $pdata['shortDescription'],
						'longDescription' => $pdata['longDescription'],
						'sku' => $pdata['sku'],
						'trailer' => $pdata['trailer'],
						'created_at' => $pdata['created_at'],
						'images' => $productsimage,
					];
				}
				$response = $this->success()->status(HttpOkay)->setValue('data', $success)->message(__('All products show successfully'));
			}
		}
		catch (ValidationException $exception) {
			$response = $this->failed()->status(HttpInvalidRequestFormat)->message($exception->getError());
		}
		catch (Throwable $exception) {
			$response = $this->error()->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function details($id = null){
		$Getproduct = Product::where('id', $id)->get();
		if (!count($Getproduct) > 0) {
			$response = $this->failed()->status(HttpResourceNotFound)->message(__(' Product not found'));
		}
		else {
			$Getproductsimages = ProductImage::where('productId', $id)->select('productId', 'path', 'tag')->get();
			$Getproductsimages->transform(function (ProductImage $item){
				return Storage::disk('secured')->url($item->path);
			});

			$success['images'] = $Getproductsimages;

			$success['details'] = $Getproduct;
			$response = $this->success()->status(HttpOkay)->setValue('data', $success)->message(__('All products show successfully'));
		}

		return $response->send();
	}

	public function categoryby(Request $request, $categoryId = null){
		$response = null;
		try {

			$validator = Validator::make($request->all(), [
				'offset' => 'required',
				'limit' => 'required',
			]);
			if ($validator->fails()) {
				return response()->json(['response' => $validator->errors()], 500);

			}
			$offset = $request->input('offset');
			$limit = $request->input('limit');
			$sort = ["field" => "id", "key" => "desc"];
			if ($request->has('sort')) {
				if ($request->sort == "Oldest") {
					$sort = ["field" => "id", "key" => "asc"];
				}
				if ($request->sort == "Cheapest") {
					$sort = ["field" => "originalPrice", "key" => "asc"];
				}
				if ($request->sort == "Highest") {
					$sort = ["field" => "originalPrice", "key" => "desc"];
				}
			}
			//if($categoryid)
			$Getproducts = Product::where('categoryId', $categoryId)->orderBy($sort['field'], $sort['key'])->offset($offset)->limit($limit)->get();

			if (count($Getproducts) == 0) {
				$response = $this->failed()->status(HttpResourceNotFound)->message(__(' Product not found'));

			}
			else {
				foreach ($Getproducts as $pdata) {
					$productsimage = ProductImage::where('productId', $pdata['id'])->select('productId', 'path', 'tag')->get();

					$productsimage->transform(function (ProductImage $item){
						return Storage::disk('secured')->url($item->path);
					});
					//$transformer->addParam('images', ':D');

					$success[] = [
						'id' => $pdata['id'],
						'name' => $pdata['name'],
						'slug' => $pdata['slug'],
						'categoryId' => $pdata['categoryId'],
						'sellerId' => $pdata['sellerId'],
						'productType' => $pdata['productType'],
						'productMode' => $pdata['productMode'],
						'listingType' => $pdata['listingType'],
						'originalPrice' => $pdata['originalPrice'],
						'offerValue' => $pdata['offerValue'],
						'offerType' => $pdata['offerType'],
						'currency' => $pdata['currency'],
						'taxRate' => $pdata['taxRate'],
						'countryId' => $pdata['countryId'],
						'stateId' => $pdata['stateId'],
						'cityId' => $pdata['cityId'],
						'zipCode' => $pdata['zipCode'],
						'address' => $pdata['address'],
						'status' => $pdata['status'],
						'promoted' => $pdata['promoted'],
						'promotionStart' => $pdata['promotionStart'],
						'promotionEnd' => $pdata['promotionEnd'],
						'visibility' => $pdata['visibility'],
						'rating' => $pdata['rating'],
						'hits' => $pdata['hits'],
						'stock' => $pdata['stock'],
						'shippingCostType' => $pdata['shippingCostType'],
						'shippingCost' => $pdata['shippingCost'],
						'soldOut' => $pdata['soldOut'],
						'deleted' => $pdata['deleted'],
						'draft' => $pdata['draft'],
						'shortDescription' => $pdata['shortDescription'],
						'longDescription' => $pdata['longDescription'],
						'sku' => $pdata['sku'],
						'trailer' => $pdata['trailer'],
						'created_at' => $pdata['created_at'],
						'images' => $productsimage,
					];
				}
				$response = $this->success()->status(HttpOkay)->setValue('data', $success)->message(__('All products show successfully'));
			}
		}
		catch (ValidationException $exception) {
			$response = $this->failed()->status(HttpInvalidRequestFormat)->message($exception->getError());
		}
		catch (Throwable $exception) {
			$response = $this->error()->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function addtocart(Request $request){
		$response = null;
		try {
			$this->requestValid($request, [
				'cartId' => ['bail', 'required'],
				'productId' => ['bail', 'required', 'numeric', 'min:1', 'max:99999'],
				'quantity' => ['bail', 'required', 'numeric', 'min:1', 'max:20'],

			]);
			$product = ProductCart::create($request->all());
			$response = $this->success()->status(HttpCreated)->setValue('data', $product)->message(__('cart add successfully'));

		}

		catch (ValidationException $exception) {
			$response = $this->failed()->status(HttpInvalidRequestFormat)->message($exception->getError());
		}
		catch (Throwable $exception) {
			$response = $this->error()->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function cart(Request $request){
		$response = null;
		try {

			if ($request->cartId != '' && $request->customerId != '') {
				$carts = ProductCart::where('cartId', $request->cartId)->Where('customerId', $request->customerId)->get();
			}
			else if ($request->customerId != '' && $request->cartId == '') {
				$carts = ProductCart::Where('customerId', $request->customerId)->get();
			}
			else if ($request->customerId == '' && $request->cartId != '') {
				$carts = ProductCart::Where('cartId', $request->cartId)->get();
			}
			else {
				$response = $this->failed()->status(HttpResourceNotFound)->message(__(' cart is empty !'));
			}

			if (count($carts) > 0) {
				$response = $this->success()->status(HttpOkay)->setValue('data', $carts)->message(__('show cart'));
			}
			else {
				$response = $this->failed()->status(HttpResourceNotFound)->message(__(' cart is empty !'));
			}

		}
		catch (ValidationException $exception) {
			$response = $this->failed()->status(HttpInvalidRequestFormat)->message($exception->getError());
		}
		catch (Throwable $exception) {
			$response = $this->error()->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function removecart($id){
		$response = null;
		try {
			$GetCartProduct = ProductCart::find($id);
			if ($GetCartProduct == null) {
				$response = $this->failed()->status(HttpResourceNotFound);
			}
			$GetCartProduct->delete();
			$response = $this->success()->status(HttpOkay)->message(__('Successfully remove this item'));
		}

		catch (ModelNotFoundException $exception) {
			$response = $this->failed()->status(HttpResourceNotFound);
		}
		catch (Exception $exception) {
			$response = $this->error()->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}
}
