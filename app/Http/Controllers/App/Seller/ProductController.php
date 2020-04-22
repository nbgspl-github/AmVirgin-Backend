<?php

namespace App\Http\Controllers\App\Seller;

use App\Classes\Arrays;
use App\Classes\Rule;
use App\Classes\Str;
use App\Constants\OfferTypes;
use App\Constants\WarrantyServiceType;
use App\Exceptions\BrandNotApprovedForSellerException;
use App\Exceptions\InvalidCategoryException;
use App\Exceptions\TokenInvalidException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\App\Seller\Products\AbstractProductController;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Interfaces\Directories;
use App\Interfaces\Tables;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\HsnCode;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\ProductToken;
use App\Resources\Products\Seller\CatalogListResource;
use App\Resources\Products\Seller\ProductEditResource;
use App\Resources\Products\Seller\ProductResource;
use App\Storage\SecuredDisk;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\ConditionallyLoadsAttributes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ProductController extends AbstractProductController{
	public function __construct(){
		parent::__construct();
	}

	public function index(): JsonResponse{
		$products = Product::startQuery()->singleVariantMode()->seller($this->guard()->id())->get();
		$products = CatalogListResource::collection($products);
		return responseApp()
			->status($products->count() > 0 ? HttpOkay : HttpNoContent)
			->message('Listing all products for this seller.')
			->setValue('payload', $products)->send();
	}

	public function edit($id): JsonResponse{
		$response = responseApp();
		try {
			$product = Product::startQuery()->seller($this->guard()->id())->firstOrFail();
			$resource = new ProductResource($product);
			$response->status(HttpOkay)->message('Listing product details.')->setValue('payload', $resource);
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

	public function show($id): JsonResponse{
		$response = responseApp();
		try {
			$product = Product::startQuery()->seller($this->guard()->id())->key($id)->firstOrFail();
			$resource = new ProductResource($product);
			$response->status(HttpOkay)->message('Listing product details.')->setValue('payload', $resource);
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

	public function store(): JsonResponse{
		$response = responseApp();
		try {
			$token = $this->validateToken();
			$outer = $this->validateOuter();
			$trailer = $this->trailerFilePath();
			$category = $this->category();
			$brand = $this->brand();
			if ($this->isInvalidCategory($category)) {
				throw new InvalidCategoryException();
			}
			if (!$this->isBrandApprovedForSeller($brand)) {
				throw new BrandNotApprovedForSellerException();
			}

			$productsPayloadCollection = new Collection();
			$product = $this->validateProductPayload($outer['payload']);
			Arrays::replaceValues($product, [
				'categoryId' => $category->id(),
				'brandId' => $brand->id(),
				'sellerId' => $this->guard()->id(),
				'type' => Product::Type['Simple'],
				'currency' => $outer['currency'],
				'description' => $outer['description'] ?? Str::Null,
				'taxRate' => HsnCode::find($product['hsn'])->taxRate(),
				'trailer' => $trailer,
				'group' => $token,
				'discount' => $this->calculateDiscount($product['originalPrice'], $product['sellingPrice']),
				'maxQuantityPerOrder' => $product['maxQuantityPerOrder'] ?? 10,
			]);
			$attributes = $product['attributes'];
			$primaryIndex = $product['primaryImageIndex'];
			$currentIndex = 0;
			$images = collect(isset($product['files']) ? $product['files'] : [])->transform(function (UploadedFile $file) use (&$currentIndex, $primaryIndex, &$product){
				$file = SecuredDisk::access()->putFile(Directories::ProductImage, $file);
				if ($currentIndex++ == $primaryIndex) {
					$product['primaryImage'] = $file;
				}
				return $file;
			})->toArray();
			$productsPayloadCollection->push([
				'product' => $product,
				'attributes' => $attributes,
				'images' => $images,
			]);

			$productsPayloadCollection->each(function ($payload){
				$product = $this->storeProduct($payload['product']);
				$this->storeAttribute($product, $payload['attributes']);
				$this->storeImages($product, $payload['images']);
			});
			$didConvertAny = $this->convertAllSimpleToVariants($token);
			if (!$didConvertAny)
				$response->status(HttpCreated)->message('Product details were saved successfully.');
			else
				$response->status(HttpCreated)->message('Product variant was saved successfully.');
		}
		catch (TokenInvalidException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
		}
		catch (InvalidCategoryException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
		}
		catch (BrandNotApprovedForSellerException $exception) {
			$response->status(HttpDeniedAccess)->message($exception->getMessage());
		}
		catch (Throwable $exception) {
			dd($exception);
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function update(): JsonResponse{
		$response = responseApp();
		try {
			$outer = $this->validateOuter();
			$trailer = $this->trailerFilePath();
			$category = $this->category();
			$brand = $this->brand();
			$sessionUid = $this->sessionUuid();
			if ($this->isInvalidCategory($category)) {
				throw new InvalidCategoryException();
			}
			if (!$this->isBrandApprovedForSeller($brand)) {
				throw new BrandNotApprovedForSellerException();
			}

			$productsPayloadCollection = new Collection();
			if ($this->isVariantType()) {
				collect($outer['payload'])->each(function ($variant) use (&$productsPayloadCollection, $category, $brand, $trailer, $outer, $sessionUid){
					$variant = $this->validateProductPayload($variant);
					Arrays::replaceValues($variant, [
						'categoryId' => $category->id(),
						'brandId' => $brand->id(),
						'sellerId' => $this->guard()->id(),
						'type' => Product::Type['Variant'],
						'currency' => $outer['currency'],
						'description' => $outer['description'],
						'taxRate' => HsnCode::find($variant['hsn'])->taxRate(),
						'trailer' => $trailer,
						'group' => $sessionUid,
						'discount' => $this->calculateDiscount($variant['originalPrice'], $variant['sellingPrice']),
					]);
					$attributes = $variant['attributes'];
					$primaryIndex = $variant['primaryImageIndex'];
					$currentIndex = 0;
					$images = collect(isset($variant['files']) ? $variant['files'] : [])->transform(function (UploadedFile $file) use (&$currentIndex, $primaryIndex, &$variant){
						$file = SecuredDisk::access()->putFile(Directories::ProductImage, $file);
						if ($currentIndex++ == $primaryIndex) {
							$variant['primaryImage'] = $file;
						}
						return $file;
					})->toArray();
					$productsPayloadCollection->push([
						'product' => $variant,
						'attributes' => $attributes,
						'images' => $images,
					]);
				});
			}
			else {
				$variant = $this->validateProductPayload($outer['payload']);
				Arrays::replaceValues($variant, [
					'categoryId' => $category->id(),
					'brandId' => $brand->id(),
					'sellerId' => $this->guard()->id(),
					'type' => Product::Type['Simple'],
					'currency' => $outer['currency'],
					'description' => $outer['description'],
					'taxRate' => HsnCode::find($variant['hsn'])->taxRate(),
					'trailer' => $trailer,
					'group' => $sessionUid,
					'discount' => $this->calculateDiscount($variant['originalPrice'], $variant['sellingPrice']),
				]);
				$attributes = $variant['attributes'];
				$primaryIndex = $variant['primaryImageIndex'];
				$currentIndex = 0;
				$images = collect(isset($variant['files']) ? $variant['files'] : [])->transform(function (UploadedFile $file) use (&$currentIndex, $primaryIndex, &$variant){
					$file = SecuredDisk::access()->putFile(Directories::ProductImage, $file);
					if ($currentIndex++ == $primaryIndex) {
						$variant['primaryImage'] = $file;
					}
					return $file;
				})->toArray();
				$productsPayloadCollection->push([
					'product' => $variant,
					'attributes' => $attributes,
					'images' => $images,
				]);
			}

			$productsPayloadCollection->each(function ($payload){
				$product = $this->storeProduct($payload['product']);
				$this->storeAttribute($product, $payload['attributes']);
				$this->storeImages($product, $payload['images']);
			});
			$response->status(HttpCreated)->message('Product details were saved successfully.');
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
		}
		catch (InvalidCategoryException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
		}
		catch (BrandNotApprovedForSellerException $exception) {
			$response->status(HttpDeniedAccess)->message($exception->getMessage());
		}
		catch (Throwable $exception) {
			dd($exception);
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function delete($id): JsonResponse{
		$response = responseApp();
		try {
			$product = Product::startQuery()->seller($this->guard()->id())->key($id)->firstOrFail();
			$product->delete();
			$response->status(HttpOkay)->message('Product deleted successfully.');
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could no find product for that key.');
		}
		catch (Throwable $exception) {
			$response->status(HttpResourceNotFound)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function token(){
		$token = $this->sessionUuid();
		$productToken = ProductToken::updateOrCreate(
			[
				'token' => $token,
				'sellerId' => $this->guard()->id(),
			],
			[
				'token' => $token,
				'sellerId' => $this->guard()->id(),
				'createdFor' => request()->ip(),
				'validUntil' => Carbon::now()->addHours(2)->toDateTimeString(),
			]
		);
		return responseApp()->setValue('payload', [
			'token' => $productToken->token(),
			'validUntil' => $productToken->validUntil(),
		])->status(HttpOkay)->message('Token generated successfully.')->send();
	}
}