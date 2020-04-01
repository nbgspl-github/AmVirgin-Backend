<?php

namespace App\Http\Controllers\App\Seller;

use App\Classes\Arrays;
use App\Classes\Rule;
use App\Classes\Str;
use App\Constants\OfferTypes;
use App\Constants\WarrantyServiceType;
use App\Exceptions\InvalidCategoryException;
use App\Exceptions\ValidationException;
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

class ProductController extends ExtendedResourceController{
	use ValidatesRequest;

	protected array $rules;

	public const attributeFormat = [
		[
			'key' => 1,
			'value' => 'L',
		],
		[
			'key' => 2,
			'value' => 'Black',
		],
		[
			'key' => 3,
			'value' => 'Solid',
		],
	];

	public function __construct(){
		parent::__construct();
		$this->rules = [
			'store' => [
				'name' => ['bail', 'required', 'string', Rule::minimum(1), Rule::maximum(500)],
				'categoryId' => ['bail', 'required', Rule::existsPrimary(Tables::Categories)],
				'brandId' => ['bail', 'required', Rule::existsPrimary(Tables::Brands), Rule::exists(Tables::SellerBrands, 'brandId')->where('status', 'approved')],
				'listingStatus' => ['bail', 'required', 'string', Rule::in([Product::ListingStatus['Active'], Product::ListingStatus['Inactive']])],
				'type' => ['bail', 'required', 'string', Rule::in([Product::Type['Simple'], Product::Type['Variant']])],
				'styleCode' => ['bail', 'required', 'string', 'min:8', 'max:255'],
				'originalPrice' => ['bail', 'required', 'numeric', 'min:1', 'max:10000000'],
				'sellingPrice' => ['bail', 'required', 'numeric', 'min:1', 'lte:originalPrice'],
				'fulfillmentBy' => ['bail', 'required', Rule::in([Product::FulfillmentBy['Seller'], Product::FulfillmentBy['SellerSmart']])],
				'hsn' => ['bail', 'required', Rule::existsPrimary(Tables::HsnCodes, 'hsnCode')],
				'currency' => ['bail', 'nullable', 'string', 'min:2', 'max:5', Rule::exists(Tables::Currencies, 'code')],
				'stock' => ['bail', 'required', 'numeric', 'min:0', RuleMaxStock],
				'description' => ['bail', 'required', 'string', 'min:1', 'max:2000'],
				'sku' => ['bail', 'required', 'string', 'min:8', 'max:100'],
				'trailer' => ['bail', 'nullable', 'mimes:mp4', 'min:1', 'max:100000'],
				'procurementSla' => ['bail', 'required', 'numeric', Rule::minimum(Product::ProcurementSLA['Minimum']), Rule::maximum(Product::ProcurementSLA['Maximum'])],
				'localShippingCost' => ['bail', 'required', 'numeric', Rule::minimum(Product::ShippingCost['Local']['Minimum']), Rule::maximum(Product::ShippingCost['Local']['Maximum'])],
				'zonalShippingCost' => ['bail', 'required', 'numeric', Rule::minimum(Product::ShippingCost['Zonal']['Minimum']), Rule::maximum(Product::ShippingCost['Zonal']['Maximum'])],
				'internationalShippingCost' => ['bail', 'required', 'numeric', Rule::minimum(Product::ShippingCost['International']['Minimum']), Rule::maximum(Product::ShippingCost['International']['Maximum'])],
				'packageWeight' => ['bail', 'required', 'numeric', Rule::minimum(Product::Weight['Minimum']), Rule::maximum(Product::Weight['Maximum'])],
				'packageLength' => ['bail', 'required', 'numeric', Rule::minimum(Product::Dimensions['Length']['Minimum']), Rule::maximum(Product::Dimensions['Length']['Maximum'])],
				'packageBreadth' => ['bail', 'required', 'numeric', Rule::minimum(Product::Dimensions['Breadth']['Minimum']), Rule::maximum(Product::Dimensions['Breadth']['Maximum'])],
				'packageHeight' => ['bail', 'required', 'numeric', Rule::minimum(Product::Dimensions['Height']['Minimum']), Rule::maximum(Product::Dimensions['Height']['Maximum'])],
				'domesticWarranty' => ['bail', 'required', 'numeric', Rule::minimum(Product::Warranty['Domestic']['Minimum']), Rule::maximum(Product::Warranty['Domestic']['Maximum'])],
				'internationalWarranty' => ['bail', 'required', 'numeric', Rule::minimum(Product::Warranty['International']['Minimum']), Rule::maximum(Product::Warranty['International']['Maximum'])],
				'warrantySummary' => ['bail', 'required', 'string', 'min:1', 'max:100000'],
				'warrantyServiceType' => ['bail', 'required', 'string', Rule::in([WarrantyServiceType::OnSite, WarrantyServiceType::WalkIn])],
				'coveredInWarranty' => ['bail', 'required', 'string', 'min:1', 'max:100000'],
				'notCoveredInWarranty' => ['bail', 'required', 'string', 'min:1', 'max:100000'],
				'maxQuantityPerOrder' => ['bail', 'required', 'numeric', 'min:1', 'max:1000'],
				'primaryImageIndex' => ['bail', 'required', 'numeric', Rule::minimum(0), Rule::maximum(8)],
				'files' => ['bail', 'nullable', 'min:1', 'max:8'],
				'files.*' => ['bail', 'nullable', 'mimes:jpg,jpeg,png,bmp', 'min:1', 'max:5120'],
				'attributes' => ['bail', 'required'],
				'variants' => ['bail', 'required_if:type,variant'],
			],
			'update' => [
			],
		];
	}

	public function index(){
		$response = responseApp();
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

	public function store(){
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules['store']);
			/**
			 * Verify that this category has no children.
			 */
			$category = Category::retrieve($validated['categoryId']);
			if ($category->children()->count() > 0)
				throw new InvalidCategoryException();

			/**
			 * Calculating tax rate from HSN code.
			 */
			$validated['taxRate'] = HsnCode::find($validated['hsn'])->taxRate();

			/**
			 * Putting adequate seller Id read from auth.
			 */
			$validated['sellerId'] = $this->guard()->id();

			$product = Product::create($validated);

			/**
			 * Generating Product Name
			 * 1.) Collect all attribute models using attribute keys coming in the request.
			 * 2.) Filter out the ones having productNameSegment as false
			 * 3.) Sort the filtered collection in ascending order of segmentPriority
			 * 4.) Append value of each attribute in this order while also checking for the ones having multiValue true
			 * 5.) If any attribute has multiValue enabled, we simply append all their values at parent's index
			 * 6.) Prepend this string with the name of brand
			 */

			$attributes = Arrays::isArray($validated['attributes']) ? $validated['attributes'] : jsonDecodeArray($validated['attributes']);
			Arrays::each($attributes, function ($attribute) use ($product){
				$attribute = Attribute::retrieve($attribute['key']);
				if ($attribute != null) {
					ProductAttribute::create([
						'productId' => $product->id(),
						'attributeId' => $attribute['key'],
						'value' => Arrays::isArray($attribute['value']) ? Str::join('::', $attribute['value']) : $attribute['value'],
					]);
				}
			});

			$variants = Arrays::isArray($validated['variants']) ? $validated['variants'] : jsonDecodeArray($validated['variants']);
			$inherited = $validated;
			Arrays::each($variants, function ($variant) use (&$inherited, $product, $attributes){
				Arrays::replaceValues($inherited, [
					'parentId' => $product->id(),
					'name' => $variant['name'],
					'stock' => $variant['stock'],
					'sellingPrice' => $variant['sellingPrice'],
					'sku' => $variant['sku'],
				]);
				$variantProduct = Product::create($inherited);
				Arrays::each($attributes, function ($attribute) use ($variantProduct){
					ProductAttribute::create([
						'productId' => $variantProduct->id(),
						'attributeId' => $attribute['key'],
						'value' => Arrays::isArray($attribute['value']) ? Str::join('::', $attribute['value']) : $attribute['value'],
					]);
				});
			});

			/**
			 * Storing Product Images and Response Collection of Images
			 */
			$images = Arrays::Empty;
			$count = 0;
			collect(request()->file('files'))->each(function (UploadedFile $uploadedFile) use ($product, &$images, &$count, $validated){
				$path = SecuredDisk::access()->putFile(Directories::ProductImage, $uploadedFile);
				ProductImage::create(['productId' => $product->id(), 'path' => $path]);
				Arrays::push($images, $path);
				if ($count++ == $validated['primaryImageIndex']) {
					$product->update([
						'primaryImage' => $path,
					]);
				}
			});

			$response->status(HttpCreated)->setValue('data', $product)->setValue('images', $images)->message('Product details were saved successfully.');
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
		}
		catch (InvalidCategoryException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function show($id){
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

	public function update($id){
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
				'domesticWarranty' => $payload->domesticWarranty,
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
			collect(jsonDecodeArray($validated['attributes']))->each(function ($item) use ($product){
				$attribute = Attribute::retrieve($item['key']);
				if ($attribute != null) {
					collect($item['values'])->each(function ($value) use ($attribute, $item, $product){
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
			collect(\request()->file('files'))->each(function (UploadedFile $uploadedFile) use ($product){
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

	public function delete($id){
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

	protected function guard(){
		return auth('seller-api');
	}
}