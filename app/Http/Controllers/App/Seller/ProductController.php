<?php

namespace App\Http\Controllers\App\Seller;

use App\Classes\Arrays;
use App\Classes\Rule;
use App\Classes\Str;
use App\Constants\OfferTypes;
use App\Constants\WarrantyServiceType;
use App\Exceptions\BrandNotApprovedForSellerException;
use App\Exceptions\InvalidCategoryException;
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
use App\Resources\Products\Seller\ProductEditResource;
use App\Resources\Products\Seller\ProductResource;
use App\Storage\SecuredDisk;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\ConditionallyLoadsAttributes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ProductController extends AbstractProductController{
	public function __construct(){
		parent::__construct();
	}

	public function store(){
		$response = responseApp();
		try {
			$outer = $this->validateOuter();
			$category = $this->category();
			$brand = $this->brand();
			if ($this->isInvalidCategory($category)) {
				throw new InvalidCategoryException();
			}
			if (!$this->isBrandApprovedForSeller($brand)) {
				throw new BrandNotApprovedForSellerException();
			}
			$trailer = $this->trailerFilePath();

			if ($this->isVariantType()) {
				foreach ($outer['payload'] as $payload) {
					$productPayload = $payload;
					Arrays::replaceValues($productPayload, [
						'categoryId' => $category->id(),
						'brandId' => $brand->id(),
						'sellerId' => $this->guard()->id(),
						'type' => Product::Type['Variant'],
						'currency' => $outer['currency'],
						'description' => $outer['description'],
						'taxRate' => HsnCode::find($productPayload['hsn'])->taxRate(),
						'trailer' => $trailer,
						'group' => $this->sessionUuid(),
						'discount' => $this->calculateDiscount($productPayload['originalPrice'], $productPayload['sellingPrice']),
						'primaryImage' => Str::Empty,
					]);
					$product = $this->storeProduct($productPayload);
					foreach ($payload['attributes'] as $attributePayload) {
						$this->storeAttribute($product, $attributePayload);
					}
				}
			}
			else {
				$productPayload = $outer['payload'];
				Arrays::replaceValues($productPayload, [
					'categoryId' => $category->id(),
					'brandId' => $brand->id(),
					'sellerId' => $this->guard()->id(),
					'type' => Product::Type['Variant'],
					'currency' => $outer['currency'],
					'description' => $outer['description'],
					'taxRate' => HsnCode::find($productPayload['hsn'])->taxRate(),
					'trailer' => $trailer,
					'group' => $this->sessionUuid(),
					'discount' => $this->calculateDiscount($productPayload['originalPrice'], $productPayload['sellingPrice']),
					'primaryImage' => Str::Empty,
				]);
				$product = $this->storeProduct($productPayload);
				foreach ($productPayload['attributes'] as $attributePayload) {
					$this->storeAttribute($product, $attributePayload);
				}
			}

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
//		finally {
//			return $response->send();
//		}
		return $response->send();
	}
}