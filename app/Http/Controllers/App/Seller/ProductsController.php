<?php

namespace App\Http\Controllers\App\Seller;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Base\ResourceController;
use App\Models\Product;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Throwable;

class ProductsController extends ResourceController{
	use ValidatesRequest;
	use FluentResponse;

	public function __construct(){
		parent::__construct();
		$this->ruleSet->load('rules.seller.product');
	}

	public function index(){

	}

	public function edit($id){

	}

	public function store(Request $request){
		$sellerId = $this->user()->getKey();
		$response = $this->response();
		try {
			$payload = $this->requestValid($request, $this->rules('store'));
			$product = Product::create([
				'name' => $payload['productName'],
				'slug' => Str::slug($payload['productName']),
				'categoryId' => $payload['categoryId'],
				'sellerId' => $sellerId,
				'productType' => $payload['productType'],
				'productMode' => $payload['productMode'],
				'listingType' => $payload['listingType'],
				'originalPrice' => $payload['originalPrice'],
				'offerValue' => $payload['offerValue'],
				'offerType' => $payload['offerType'],
				'currency' => $payload['currency'],
				'taxRate' => $payload['taxRate'],
				'countryId' => $payload['countryId'],
				'stateId' => $payload['stateId'],
				'cityId' => $payload['cityId'],
				'zipCode' => $payload['zipCode'],
				'address' => $payload['address'],
				'status' => $payload['status'],
				'promoted' => $payload['promoted'],
				'promotionStart' => $payload['promotionStart'],
				'promotionEnd' => $payload['promotionEnd'],
				'visibility' => $payload['visibility'],
				'stock' => $payload['stock'],
				'shippingCostType' => $payload['shippingCostType'],
				'shippingCost' => $payload['shippingCost'],
				'soldOut' => $payload['stock'] < 1,
				'draft' => $payload['draft'],
				'shortDescription' => $payload['shortDescription'],
				'longDescription' => $payload['longDescription'],
				'sku' => $payload['sku'],
			]);
			$response->status(HttpCreated)->setValue('data', $product)->message(__('strings.product.store.success'));
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

	public function show($id){
		$sellerId = $this->user()->getKey();
		$response = null;
		try {
			$product = $this->retrieveChild(function ($query) use ($sellerId, $id){
				$query->where('sellerId', $sellerId)->where('id', $id);
			});
			$response = $this->success()->setValue('data', $product);
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

	public function update(Request $request, $id){

	}

	public function patch(Request $request, $id){

	}

	public function delete($id){

	}

	protected function parentProvider(){
		return null;
	}

	protected function provider(){
		return Product::class;
	}

	protected function resourceConverter(Model $model){

	}

	protected function collectionConverter(Collection $collection){

	}

	protected function guard(){
		return Auth::guard('seller-api');
	}

	private function isSoldOut(Request $request){
		return $request->stock < 1;
	}
}