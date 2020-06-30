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
use App\Queries\ProductQuery;
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
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductController extends AbstractProductController{
	public function index(): JsonResponse{
		$per_page = Request::get('per_page') ?? '';
		if (empty($per_page)) {
			$per_page = 10;
		}
		$page_no = Request::get('page_no') ?? '';
		if (empty($page_no)) {
			$page_no = 1;
		}
		$recordLimit = $per_page * $page_no;
		$products = Product::startQuery()->singleVariantMode()->seller($this->guard()->id())->skip($recordLimit)->take($per_page)->get();
		$products = CatalogListResource::collection($products);
		return responseApp()
			->status($products->count() > 0 ? HttpOkay : HttpNoContent)
			->message('Listing all products for this seller.')
			->setValue('payload', $products)->send();
	}

	public function edit($id): JsonResponse{
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
				'group' => $token,
				'discount' => $this->calculateDiscount($product['originalPrice'], $product['sellingPrice']),
				'maxQuantityPerOrder' => $product['maxQuantityPerOrder'] ?? 10,
			]);
			$attributes = $product['attributes'];
			$primaryIndex = $product['primaryImageIndex'];
			$currentIndex = 0;
			$images = collect($product['files'] ?? [])->transform(function (UploadedFile $file) use (&$currentIndex, $primaryIndex, &$product){
				$file = SecuredDisk::access()->putFile(Directories::ProductImage, $file);
				if ($currentIndex++ === $primaryIndex) {
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
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function update($id): JsonResponse{
		$response = responseApp();
		try {
			$payload = $this->validateUpdate();
			$product = Product::startQuery()->displayable()->key($id)->useAuth()->firstOrFail();
			$product->update($payload);
			$primaryIndex = $payload['primaryImageIndex'] ?? 0;
			$currentIndex = 0;
			$images = collect($payload['files'] ?? [])->transform(function (UploadedFile $file) use (&$currentIndex, $primaryIndex, &$product){
				$file = SecuredDisk::access()->putFile(Directories::ProductImage, $file);
				if ($currentIndex++ === $primaryIndex) {
					$product['primaryImage'] = $file;
				}
				return $file;
			})->toArray();
			$this->storeImages($product, $images);
			$response->status(HttpCreated)->message('Product details were updated successfully.');
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message($exception->getMessage());
		}
		catch (Throwable $exception) {
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
			$response->status(HttpResourceNotFound)->message('Could not find product for that key.');
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

	public function changeStatus($id='')
	{
		# code...
	}
}