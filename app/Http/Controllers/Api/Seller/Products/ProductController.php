<?php

namespace App\Http\Controllers\Api\Seller\Products;

use App\Exceptions\BrandNotApprovedForSellerException;
use App\Exceptions\InvalidCategoryException;
use App\Exceptions\TokenInvalidException;
use App\Exceptions\ValidationException;
use App\Library\Enums\Common\Directories;
use App\Library\Utils\Extensions\Arrays;
use App\Library\Utils\Extensions\Str;
use App\Library\Utils\Uploads;
use App\Models\HsnCode;
use App\Models\Product;
use App\Models\ProductToken;
use App\Resources\Products\Seller\CatalogListResource;
use App\Resources\Products\Seller\ProductResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Throwable;

class ProductController extends AbstractProductController
{
	public function index (): JsonResponse
	{
		$per_page = request()->get('per_page') ?? '';
		$per_no = request()->get('page') ?? '1';
		if (empty($per_page)) {
			$per_page = 10;
		}

		$productRecord = Product::startQuery()->singleVariantMode()->seller($this->guard()->id());
		if (!empty(request()->get('status'))) {
			if (request('status') == 'active' || request('status') == 'inactive') {
				$productRecord->withWhere('listingStatus', request()->get('status'));
			} else {
				if (request('status') == 'underprocess') {
					$productRecord->withWhere('approved', false);
				} else if (request('status') == 'approved') {
					$productRecord->withWhere('approved', true);
				} else {

				}
			}
		}
		if (!empty(request()->get('query'))) {
			$keywords = request()->get('query');
			$productRecord->search($keywords, 'name');
			$productRecord->orSearch($keywords, 'slug');
			$productRecord->orSearch($keywords, 'listingStatus');
			$productRecord->orSearch($keywords, 'created_at');
		}
		$products = $productRecord->paginate($per_page);

		$total = count($products);
		$totalRec = $products->total();
		$meta = [
			'pagination' => [
				'pages' => countRequiredPages($totalRec, $per_page),
				'current_page' => $per_no,
				'items' => ['total' => $total, 'totalRec' => $totalRec, 'chunk' => $per_page],
			],
		];
		$products = CatalogListResource::collection($products);
		return responseApp()
			->status($products->count() > 0 ? \Illuminate\Http\Response::HTTP_OK : \Illuminate\Http\Response::HTTP_NO_CONTENT)
			->message('Listing all products for this seller.')
			->setValue('meta', $meta)
			->setValue('payload', $products)
			->send();
	}

	public function edit ($id): JsonResponse
	{
		$response = responseApp();
		try {
			$product = Product::startQuery()->seller($this->guard()->id())->key($id)->firstOrFail();
			$resource = new ProductResource($product);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Listing product details.')->setValue('payload', $resource);
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find product for that key.');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function show ($id): JsonResponse
	{
		$response = responseApp();
		try {
			$product = Product::startQuery()->seller($this->guard()->id())->key($id)->firstOrFail();
			$resource = new ProductResource($product);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Listing product details.')->setValue('payload', $resource);
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find product for that key.');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function store (): JsonResponse
	{
		$response = responseApp();
		try {
			$token = $this->validateToken();
			$outer = $this->validateOuter();
			$category = $this->category();
			$brand = $this->brand();
			if ($this->isInvalidCategory($category)) {
				throw new InvalidCategoryException();
			}
//            if (!$this->isBrandApprovedForSeller($brand)) {
//                throw new BrandNotApprovedForSellerException();
//            }

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
			$images = collect($product['files'] ?? [])->transform(function (UploadedFile $file) use (&$currentIndex, $primaryIndex, &$product) {
				$file = Uploads::access()->putFile(Directories::ProductImage, $file);
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

			$productsPayloadCollection->each(function ($payload) {
				$product = $this->storeProduct($payload['product']);
				$this->storeAttribute($product, $payload['attributes']);
				$this->storeImages($product, $payload['images']);
			});
			$didConvertAny = $this->convertAllSimpleToVariants($token);
			if (!$didConvertAny)
				$response->status(\Illuminate\Http\Response::HTTP_CREATED)->message('Product details were saved successfully.');
			else
				$response->status(\Illuminate\Http\Response::HTTP_CREATED)->message('Product variant was saved successfully.');
		} catch (TokenInvalidException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} catch (InvalidCategoryException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} catch (BrandNotApprovedForSellerException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_FORBIDDEN)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function update ($id): JsonResponse
	{
		$response = responseApp();
		try {
			$payload = $this->validateUpdate();
			$product = Product::startQuery()->displayable()->key($id)->useAuth()->firstOrFail();
			$product->update($payload);
			$primaryIndex = $payload['primaryImageIndex'] ?? 0;
			$currentIndex = 0;
			$images = collect($payload['files'] ?? [])->transform(function (UploadedFile $file) use (&$currentIndex, $primaryIndex, &$product) {
				$file = Uploads::access()->putFile(Directories::ProductImage, $file);
				if ($currentIndex++ === $primaryIndex) {
					$product['primaryImage'] = $file;
				}
				return $file;
			})->toArray();
			$this->storeImages($product, $images);
			$response->status(\Illuminate\Http\Response::HTTP_CREATED)->message('Product details were updated successfully.');
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function delete ($id): JsonResponse
	{
		$response = responseApp();
		try {
			$product = Product::startQuery()->seller($this->guard()->id())->key($id)->firstOrFail();
			$product->delete();
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Product deleted successfully.');
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find product for that key.');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function token ()
	{
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
		])->status(\Illuminate\Http\Response::HTTP_OK)->message('Token generated successfully.')->send();
	}

	public function changeStatus (int $id): JsonResponse
	{
		$response = responseApp();
		try {
			$product = Product::where(['id' => $id, 'sellerId' => auth('seller-api')->id()])->first();
			if (!empty($product) || !empty(request('status'))) {
				$product->listingStatus = request('status');
				$product->update();
				$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Product status updated successfully.');
			} else {
				$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Product status is required.');
			}
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find product for that key.');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}