<?php

namespace App\Http\Controllers\App\Seller;

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

class ProductsController extends ExtendedResourceController{
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
				'categoryId' => ['bail', 'required', Rule::existsPrimary(Tables::Categories)],
				'brandId' => ['bail', 'required', Rule::existsPrimary(Tables::Brands), Rule::exists(Tables::SellerBrands, 'brandId')->where('status', 'approved')],
				'listingStatus' => ['bail', 'required', 'string', Rule::in([Product::ListingStatus['Active'], Product::ListingStatus['Inactive']])],
				'styleCode' => ['bail', 'required', 'string', 'min:8', 'max:255'],
				'originalPrice' => ['bail', 'required', 'numeric', 'min:1', 'max:10000000'],
				'sellingPrice' => ['bail', 'required', 'numeric', 'min:1', 'lte:originalPrice'],
				'fulfillmentBy' => ['bail', 'required', Rule::in([Product::FulfillmentBy['Seller'], Product::FulfillmentBy['SellerSmart']])],
				'hsn' => ['bail', 'required', Rule::existsPrimary(Tables::HsnCodes, 'hsnCode')],
				'currency' => ['bail', 'nullable', 'string', 'min:2', 'max:5', Rule::exists(Tables::Currencies, 'code')],
				'stock' => ['bail', 'required', 'numeric', 'min:0', RuleMaxStock],
				'shortDescription' => ['bail', 'required', 'string', 'min:1', 'max:1000'],
				'longDescription' => ['bail', 'required', 'string', 'min:1', 'max:100000'],
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
				'files.*' => ['bail', 'nullable', 'mimes:jpg,jpeg,png,bmp', 'min:1', 'max:5120'],
				'attributes' => ['bail', 'required'],
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
			/**
			 * Create product with no name initially.
			 */
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

			$segments = array_fill(0, 12, Str::Empty);
			$segments[0] = Brand::retrieve($validated['brandId'])->name();
			$segments[11] = $category->name();
			collect($validated['attributes'])->each(function ($item) use ($product, &$segments){
				$attribute = Attribute::retrieve($item['key']);
				if ($attribute != null) {
					$sellerInterfaceType = $attribute->sellerInterfaceType();
					$hasValues = Str::equals($sellerInterfaceType, Attribute::SellerInterfaceType['DropDown']) || Str::equals($sellerInterfaceType, Attribute::SellerInterfaceType['Radio']);
					if (!$hasValues) ProductAttribute::create([
						'productId' => 10,
						'attributeId' => $attribute->id(),
						'value' => $item['data']['value'],
					]);
					else ProductAttribute::create([
						'productId' => 10,
						'attributeId' => $attribute->id(),
						'valueId' => $item['data']['key'],
						'value' => $item['data']['value'],
					]);

					if ($attribute->productNameSegment() && $attribute->segmentPriority() != 0) {
						$finalValue = $item['data']['value'];
						$separated = Str::split('/', $finalValue);
						if ($attribute->multiValue() && count($separated) > 1) {
							$finalValue = Str::join(Str::WhiteSpace, $separated);
						}
						$segments[$attribute->segmentPriority()] = $finalValue;
					}
				}
			});
			$product->update(['name' => Str::trimExtraWhiteSpaces(Str::join(Str::WhiteSpace, $segments))]);
//			collect(request()->file('files'))->each(function (UploadedFile $uploadedFile) use ($product){
//				ProductImage::create([
//					'productId' => $product->getKey(),
//					'path' => SecuredDisk::access()->putFile(Directories::ProductImage, $uploadedFile),
//					'tag' => sprintf('product-%d-images', $product->getKey()),
//				]);
//			});
//			$images = $product->images()->get()->transform(fn(ProductImage $productImage) => [
//				'url' => SecuredDisk::access()->exists($productImage->path) ? SecuredDisk::access()->url($productImage->path) : null,
//			]);
//			$images = $images->filter()->values();
//			$response->status(HttpCreated)->setValue('data', $product)->setValue('images', $images)->message('Product details were saved successfully.');
			$response->status(HttpOkay)->message('Saved');
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