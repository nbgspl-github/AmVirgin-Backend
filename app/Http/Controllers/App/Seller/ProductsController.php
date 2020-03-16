<?php

namespace App\Http\Controllers\App\Seller;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Interfaces\Directories;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Resources\Products\Seller\ProductEditResource;
use App\Resources\Products\Seller\ProductResource;
use App\Storage\SecuredDisk;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\ConditionallyLoadsAttributes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ProductsController extends ExtendedResourceController {
	use ValidatesRequest;
	use ConditionallyLoadsAttributes;

	public function __construct() {
		parent::__construct();
		$this->ruleSet->load('rules.seller.product');
	}

	public function index() {
		$response = responseApp();
		try {
			$products = Product::where([
				['sellerId', $this->user()->getKey()],
				['deleted', false],
				['soldOut', false],
				['draft', false],
			])->get();
			$products = ProductResource::collection($products);
			$response->status(HttpOkay)->message(function () use ($products) {
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

	public function edit($id) {
		$response = responseApp();
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
			$response->status(HttpResourceNotFound)->message('Could not find product for that key.');
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function store() {
		$response = responseApp();
		try {
			$validated = $this->requestValid(\request(), $this->rules('store'));
			$payload = (object)$validated;
			$product = Product::create([
				'name' => $payload->productName,
				'categoryId' => $payload->categoryId,
				'sellerId' => $this->user()->getKey(),
				'productType' => $payload->productType,
				'productMode' => $payload->productMode,
				'listingType' => $payload->listingType,
				'originalPrice' => $payload->originalPrice,
				'sellingPrice' => $payload->sellingPrice,
				'hsn' => $payload->HSN,
				'taxCode' => $payload->taxCode,
				'fullfilmentBy' => $payload->fullfilmentBy,
				'procurementSla' => $payload->procurementSla,
				'localShippingCost' => $payload->localShippingCost,
				'zonalShippingCost' => $payload->zonalShippingCost,
				'internationalShippingCost' => $payload->internationalShippingCost,
				'packageWeight' => $payload->packageWeight,
				'packageLength' => $payload->packageLength,
				'packageHeight' => $payload->packageHeight,
				'idealFor' => $payload->idealFor,
				'videoUrl' => $payload->videoUrl,
				'domesticwarranty' => $payload->domesticwarranty,
				'internationalWarranty' => $payload->internationalWarranty,
				'warrantySummary' => $payload->warrantySummary,
				'warrantyServiceType' => $payload->warrantyServiceType,
				'coveredInWarranty' => $payload->coveredInWarranty,
				'notCoveredInWarranty' => $payload->notCoveredInWarranty,
				'offerValue' => $payload->offerValue,
				'offerType' => $payload->offerType,
				'currency' => $payload->currency,
				'taxRate' => $payload->taxRate,
				'countryId' => $payload->countryId,
				'stateId' => $payload->stateId,
				'cityId' => $payload->cityId,
				'zipCode' => $payload->zipCode,
				'address' => $payload->address,
				'status' => $payload->status,
				'promoted' => $payload->promoted,
				'promotionStart' => date('Y-m-d H:i:s', strtotime($payload->promotionStart)),
				'promotionEnd' => date('Y-m-d H:i:s', strtotime($payload->promotionEnd)),
				'visibility' => $payload->visibility,
				'stock' => $payload->stock,
				'shippingCostType' => $payload->shippingCostType,
				'shippingCost' => $payload->shippingCost,
				'soldOut' => request('stock') < 1,
				'draft' => $payload->draft,
				'shortDescription' => $payload->shortDescription,
				'longDescription' => $payload->longDescription,
				'sku' => $payload->sku,
			]);
			collect(jsonDecodeArray($validated['attributes']))->each(function ($item) use ($product) {
				$attribute = Attribute::retrieve($item['key']);
				if ($attribute != null) {
					collect($item['values'])->each(function ($value) use ($attribute, $item, $product) {
						$attributeValue = AttributeValue::where([
							['attributeId', $attribute->getKey()],
							['id', $value],
						])->first();
						if ($attributeValue != null) {
							ProductAttribute::create([
								'productId' => $product->id,
								'attributeId' => $attribute->id,
								'valueId' => $attributeValue->id,
							]);
						}
					});
				}
			});
			collect(request()->file('files'))->each(function (UploadedFile $uploadedFile) use ($product) {
				ProductImage::create([
					'productId' => $product->getKey(),
					'path' => SecuredDisk::access()->putFile(Directories::ProductImage, $uploadedFile),
					'tag' => sprintf('product-%d-images', $product->getKey()),
				]);
			});
			$images = $product->images()->get()->transform(function (ProductImage $productImage) {
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

	public function show($id) {
		$response = responseApp();
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

	public function update($id) {
		$response = responseApp();
		try {
			$product = Product::retrieveThrows($id);
			$validated = $this->requestValid(request(), $this->rules('update'));
			$product->update([
				'name' => $validated['productName'],
				'categoryId' => $validated['categoryId'],
				'sellerId' => $this->user()->getKey(),
				'productType' => $validated['productType'],
				'productMode' => $validated['productMode'],
				'listingType' => $validated['listingType'],
				'originalPrice' => $validated['originalPrice'],
				'sellingPrice' => $validated->sellingPrice,
				'hsn' => $validated->HSN,
				'taxCode' => $validated->taxCode,
				'fullfilmentBy' => $validated->fullfilmentBy,
				'procurementSla' => $validated->procurementSla,
				'localShippingCost' => $validated->localShippingCost,
				'zonalShippingCost' => $validated->zonalShippingCost,
				'internationalShippingCost' => $validated->internationalShippingCost,
				'packageWeight' => $validated->packageWeight,
				'packageLength' => $validated->packageLength,
				'packageHeight' => $validated->packageHeight,
				'idealFor' => $validated->idealFor,
				'domesticwarranty' => $payload->domesticwarranty,
				'internationalWarranty' => $payload->internationalWarranty,
				'warrantySummary' => $payload->warrantySummary,
				'warrantyServiceType' => $payload->warrantyServiceType,
				'coveredInWarranty' => $payload->coveredInWarranty,
				'notCoveredInWarranty' => $payload->notCoveredInWarranty,
				'videoUrl' => $validated->videoUrl,
				'offerValue' => $validated['offerValue'],
				'offerType' => $validated['offerType'],
				'currency' => $validated['currency'],
				'taxRate' => $validated['taxRate'],
				'countryId' => $validated['countryId'],
				'stateId' => $validated['stateId'],
				'cityId' => $validated['cityId'],
				'zipCode' => $validated['zipCode'],
				'address' => $validated['address'],
				'status' => $validated['status'],
				'promoted' => $validated['promoted'],
				'promotionStart' => date('Y-m-d H:i:s', strtotime($validated['promotionStart'])),
				'promotionEnd' => date('Y-m-d H:i:s', strtotime($validated['promotionEnd'])),
				'visibility' => $validated['visibility'],
				'stock' => $validated['stock'],
				'shippingCostType' => $validated['shippingCostType'],
				'shippingCost' => $validated['shippingCost'],
				'soldOut' => \request('stock') < 1,
				'draft' => $validated['draft'],
				'shortDescription' => $validated['shortDescription'],
				'longDescription' => $validated['longDescription'],
				'sku' => $validated['sku'],
			]);
			collect(jsonDecodeArray($validated['attributes']))->each(function ($item) use ($product) {
				$attribute = Attribute::retrieve($item['key']);
				if ($attribute != null) {
					collect($item['values'])->each(function ($value) use ($attribute, $item, $product) {
						$attributeValue = AttributeValue::where([
							['attributeId', $attribute->getKey()],
							['id', $value],
						])->first();
						if ($attributeValue != null) {
							ProductAttribute::create([
								'productId' => $product->id,
								'attributeId' => $attribute->id,
								'valueId' => $attributeValue->id,
							]);
						}
					});
				}
			});
			collect(\request()->file('files'))->each(function (UploadedFile $uploadedFile) use ($product) {
				ProductImage::create([
					'productId' => $product->getKey(),
					'path' => SecuredDisk::access()->putFile(Directories::ProductImage, $uploadedFile),
					'tag' => sprintf('product-%d-images', $product->getKey()),
				]);
			});
			$response->status(HttpOkay)->message('Product details were updated successfully.');
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

	public function delete($id) {
		$response = responseApp();
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

	protected function guard() {
		return Auth::guard('seller-api');
	}
}