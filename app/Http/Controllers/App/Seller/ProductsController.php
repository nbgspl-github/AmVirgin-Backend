<?php

namespace App\Http\Controllers\App\Seller;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Base\ResourceController;
use App\Interfaces\StatusCodes;
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

class ProductsController extends ResourceController{
	use ValidatesRequest;
	use FluentResponse;

	protected $rules;

	public function __construct(){
		$this->rules = config();
	}

	public function index(){

	}

	public function edit($id){

	}

	public function store(Request $request){
		$sellerId = $this->user()->getKey();
		$response = null;
		try {
			$this->requestValid($request, $this->rules['store']);
			$product = Product::create([
				'name' => $request->productName,
				'slug' => Str::slug($request->productName),
				'categoryId' => $request->categoryId,
				'sellerId' => $sellerId,
				'productType' => $request->productType,
				'productMode' => $request->productMode,
				'listingType' => $request->listingType,
				'originalPrice' => $request->originalPrice,
				'offerValue' => $request->offerValue,
				'offerType' => $request->offerType,
				'currency' => $request->currency,
				'taxRate' => $request->taxRate,
				'countryId' => $request->countryId,
				'stateId' => $request->stateId,
				'cityId' => $request->cityId,
				'zipCode' => $request->zipCode,
				'address' => $request->address,
				'status' => $request->status,
				'promoted' => $request->promoted,
				'promotionStart' => $request->promotionStart,
				'promotionEnd' => $request->promotionEnd,
				'visibility' => $request->visibility,
				'stock' => $request->stock,
				'shippingCostType' => $request->shippingCostType,
				'shippingCost' => $request->shippingCost,
				'soldOut' => $this->isSoldOut($request),
				'draft' => $request->draft,
				'shortDescription' => $request->shortDescription,
				'longDescription' => $request->longDescription,
				'sku' => $request->sku,
			]);
			$response = $this->success()->status(StatusCodes::Created)->setValue('data', $product)->message(__('strings.product.store.success'));
		}
		catch (ValidationException $exception) {
			$response = $this->failed()->status(StatusCodes::InvalidRequestFormat)->message($exception->getError());
		}
		catch (Exception $exception) {
			$response = $this->error()->message($exception->getMessage());
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
			$response = $this->failed()->status(StatusCodes::ResourceNotFound);
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