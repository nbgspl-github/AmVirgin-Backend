<?php

namespace App\Http\Controllers\App\Seller;

use App\Constants\OfferTypes;
use App\Constants\ProductStatus;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Interfaces\Directories;
use App\Models\Product;
use App\Models\ProductImage;
use App\Resources\Products\Seller\ProductEditResource;
use App\Resources\Products\Seller\ProductResource;
use App\Storage\SecuredDisk;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\ConditionallyLoadsAttributes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class ProductsController extends ExtendedResourceController{
	use ValidatesRequest;
	use FluentResponse;
	use ConditionallyLoadsAttributes;

	public function __construct(){
		parent::__construct();
		$this->ruleSet->load('rules.seller.product');
	}

	public function index(){
		$response = $this->response();
		try {
			$products = Product::where([
				['sellerId', $this->user()->getKey()],
				['deleted', false],
				['soldOut', false],
				['draft', false],
			])->get();
			$products = ProductResource::collection($products);
			$response->status(HttpOkay)->message(function () use ($products){
				return sprintf('Found %d products by specified seller.', $products->count());
			})->setValue('data', $products);
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function edit($id){
		$response = $this->response();
		try {
			$product = Product::where([
				['sellerId', $this->user()->getKey()],
				['deleted', false],
				['id', $id],
			])->firstOrFail();
			$product = new ProductEditResource($product);
			$response->status(HttpOkay)->message('Found product details for that key.')->setValue('data', $product);
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpServerError)->message('Could not find product for that key.');
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function store(){
		$response = $this->response();
		try {
			$payload = $this->requestValid(\request(), $this->rules('store'));
			$product = Product::create([
				'name' => $payload['productName'],
				'categoryId' => $payload['categoryId'],
				'sellerId' => $this->user()->getKey(),
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
				'promotionStart' => date('Y-m-d H:i:s', strtotime($payload['promotionStart'])),
				'promotionEnd' => date('Y-m-d H:i:s', strtotime($payload['promotionEnd'])),
				'visibility' => $payload['visibility'],
				'stock' => $payload['stock'],
				'shippingCostType' => $payload['shippingCostType'],
				'shippingCost' => $payload['shippingCost'],
				'soldOut' => \request('stock') < 1,
				'draft' => $payload['draft'],
				'shortDescription' => $payload['shortDescription'],
				'longDescription' => $payload['longDescription'],
				'sku' => $payload['sku'],
			]);
			if (\request()->hasFile('files')) {
				collect(\request()->file('files'))->each(function (UploadedFile $uploadedFile) use ($product){
					ProductImage::create([
						'productId' => $product->getKey(),
						'path' => SecuredDisk::access()->putFile(Directories::ProductImage, $uploadedFile),
						'tag' => sprintf('product-%d-images', $product->getKey()),
					]);
				});
			}
			$images = $product->images()->get()->transform(function (ProductImage $productImage){
				return [
					'url' => SecuredDisk::access()->exists($productImage->path) ? SecuredDisk::access()->url($productImage->path) : null,
				];
			});
			$images = $images->filter()->values();
			$response->status(HttpCreated)->setValue('data', $product)->setValue('images', $images)->message('Product details were saved successfully.');
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
		$response = $this->response();
		try {
			$product = Product::where([
				['sellerId', $this->user()->getKey()],
				['deleted', false],
				['soldOut', false],
				['draft', false],
				['id', $id],
			])->firstOrFail();
			$product = new ProductResource($product);
			$response->status(HttpOkay)->message('Found product for the specified key.')->setValue('data', $product);
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find the product for that key.');
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function update(Request $request, $productId = null){
		$rules = $this->rules('update');

		$validator = Validator::make($request->all(), [
			'categoryId' => ['bail', 'required', 'exists:categories,id'],
			'productType' => ['bail', 'required', 'string', 'min:1', 'max:256'],
			'productMode' => ['bail', 'required', 'string', 'min:1', 'max:256'],
			'listingType' => ['bail', 'required', 'string', 'min:1', 'max:256'],
			'originalPrice' => ['bail', 'required', 'numeric', 'min:1', 'max:10000000'],
			'offerType' => ['bail', 'required', Rule::in([OfferTypes::FlatRate, OfferTypes::Percentage])], /*Since we only have two offer types for now, it's 0 and 1, later on we'll add as required.*/
			'offerValue' => ['bail', 'required', 'numeric', 'min:1', 'max:10000000'],
			'currency' => ['bail', 'nullable', 'string', 'min:2', 'max:5', 'exists:currencies,code'],
			'taxRate' => ['bail', 'required', 'numeric', 'min:0.00', 'max:99.99'],
			'countryId' => ['bail', 'required', 'exists:countries,id'],
			'stateId' => ['bail', 'required', 'numeric', 'min:1', 'max:9999999'],
			'cityId' => ['bail', 'required', 'numeric', 'min:1', RuleMaxInt],
			'zipCode' => ['bail', 'required', 'min:1', RuleMaxInt],
			'address' => ['bail', 'required', 'string', 'min:2', 'max:500'],
			'status' => ['bail', 'nullable', Rule::in([ProductStatus::DifferentStatus, ProductStatus::SomeOtherStatus, ProductStatus::SomeStatus])],
			'promoted' => ['bail', 'boolean'],
			'promotionStart' => ['bail', 'required_with:promoted', 'date'],
			'promotionEnd' => ['bail', 'required_with:promoted', 'date', 'after:promotionStart'],
			'visibility' => ['bail', 'boolean'],
			'stock' => ['bail', 'required', 'numeric', 'min:0', RuleMaxStock],
			'shippingCostType' => ['bail', 'required', Rule::in(['free', 'chargeable'])],
			'shippingCost' => ['bail', 'required_if:shippingCostType,chargeable'],
			'draft' => ['bail', 'boolean'],
			'shortDescription' => ['bail', 'required', 'string', 'min:1', 'max:1000'],
			'longDescription' => ['bail', 'required', 'string', 'min:1', 'max:5000'],
			'sku' => ['bail', 'required', 'string', 'min:1', 'max:256'],
		]);
		$data = [
			//'name'=>$request->name,
			'categoryId' => $request->categoryId,
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
			'promotionStart' => date('Y-m-d H:i:s', strtotime($request->promotionStart)),
			'promotionEnd' => date('Y-m-d H:i:s', strtotime($request->promotionEnd)),
			'visibility' => $request->visibility,
			'stock' => $request->stock,
			'shippingCostType' => $request->shippingCostType,
			'shippingCost' => $request->shippingCost,
			'soldOut' => \request('stock') < 1,
			'draft' => $request->draft,
			'shortDescription' => $request->shortDescription,
			'longDescription' => $request->longDescription,
			'sku' => $request->sku,
		];
		//updated image
		$Newimages = $request->file('files');
		if (!empty($Newimages)) {
			//delete old image
			/*$image_path = "/images/filename.ext";  // Value is not URL but directory file path
			if(File::exists($image_path)) {
			  File::delete($image_path);
			}*/
			$GetImages = ProductImage::where('productId', $productId)->get();
			if ($GetImages != null) {
				$GetImages->each(function (ProductImage $item){
					$item->delete();
				});
			}

			if (count($request->file('files')) > 0) {
				foreach ($request->file('files') as $imgdata) {
					$productimage = new ProductImage();
					$productimage->productId = $productId;
					$productimage->path = Storage::disk('secured')->putFile(Directories::ProductImage, $imgdata, 'private');
					$productimage->tag = 'Product-Image';
					$productimage->save();
				}
			}
		}

		if ($validator->fails()) {
			// $response = $this->error()->message($validator->getMessage());
			$response = $this->failed()->status(HttpResourceNotFound);
		}
		else {
			$products = Product::retrieve($productId);
			if ($products == null) {
				$response = $this->failed()->status(HttpResourceNotFound);
			}
			else {
				$update = Product::find($productId);
				Product::where('id', $productId)->update($data);
				$response = $this->success()->status(HttpOkay)->message(__('product updated successfully'));
			}

		}

		return $response->send();

	}

	public function delete($id){
		$response = $this->response();
		try {
			$product = Product::where([
				['deleted', false],
				['id', $id],
			])->firstOrFail();
			$product->setDeleted(true)->save();
			$response->status(HttpOkay)->message('Product deleted successfully.');
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find product for that key.');
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	protected function guard(){
		return Auth::guard('seller-api');
	}
}