<?php

namespace App\Http\Controllers\App\Seller\Products;

use App\Classes\Arrays;
use App\Classes\Rule;
use App\Classes\Str;
use App\Classes\Time;
use App\Constants\WarrantyServiceType;
use App\Exceptions\TokenInvalidException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Interfaces\Directories;
use App\Interfaces\Tables;
use App\Models\Attribute;
use App\Models\AttributeSet;
use App\Models\AttributeSetItem;
use App\Models\Auth\Seller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\HsnCode;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductToken;
use App\Models\SellerBrand;
use App\Storage\SecuredDisk;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use stdClass;
use Sujip\Guid\Facades\Guid;

class AbstractProductController extends ExtendedResourceController{
	use ValidatesRequest;

	protected ?Collection $items = null;
	protected array $rules;

	public function __construct(){
		parent::__construct();
		$this->rules = [
			'store' => [
				'outer' => [
					'requestToken' => ['bail', 'nullable', 'string', 'min:30', 'max:36'],
					'categoryId' => ['bail', 'required', Rule::existsPrimary(Tables::Categories)],
					'brandId' => ['bail', 'required', Rule::existsPrimary(Tables::Brands), Rule::exists(Tables::SellerBrands, 'brandId')->where('status', 'approved')],
					'currency' => ['bail', 'nullable', 'string', 'min:2', 'max:5', Rule::exists(Tables::Currencies, 'code')],
					'description' => ['bail', 'nullable', 'string', 'min:1', 'max:2000'],
					'trailer' => ['bail', 'nullable', 'mimes:mp4', 'min:1', 'max:100000'],
					'payload' => ['bail', 'required'],
				],
				'product' => [
					'name' => ['bail', 'required', 'string', Rule::minimum(1), Rule::maximum(500)],
					'listingStatus' => ['bail', 'required', 'string', Rule::in([Product::ListingStatus['Active'], Product::ListingStatus['Inactive']])],
					'originalPrice' => ['bail', 'required', 'numeric', 'min:1', 'max:10000000'],
					'sellingPrice' => ['bail', 'required', 'numeric', 'min:1', 'lte:originalPrice'],
					'fulfillmentBy' => ['bail', 'required', Rule::in([Product::FulfillmentBy['Seller'], Product::FulfillmentBy['SellerSmart']])],
					'hsn' => ['bail', 'required', Rule::existsPrimary(Tables::HsnCodes, 'hsnCode')],
					'stock' => ['bail', 'required', 'numeric', 'min:0', RuleMaxStock],
					'lowStockThreshold' => ['bail', 'nullable', 'numeric', 'min:0', 'lt:stock'],
					'sku' => ['bail', 'required', 'string', 'min:8', 'max:100'],
					'styleCode' => ['bail', 'nullable', 'string', 'min:8', 'max:255'],
					'idealFor' => ['bail', 'nullable', Rule::in(Arrays::values(Product::IdealFor))],
					'procurementSla' => ['bail', 'required', 'numeric', Rule::minimum(Product::ProcurementSLA['Minimum']), Rule::maximum(Product::ProcurementSLA['Maximum'])],
					'localShippingCost' => ['bail', 'nullable', 'numeric', Rule::minimum(Product::ShippingCost['Local']['Minimum']), Rule::maximum(Product::ShippingCost['Local']['Maximum'])],
					'zonalShippingCost' => ['bail', 'nullable', 'numeric', Rule::minimum(Product::ShippingCost['Zonal']['Minimum']), Rule::maximum(Product::ShippingCost['Zonal']['Maximum'])],
					'internationalShippingCost' => ['bail', 'required', 'numeric', Rule::minimum(Product::ShippingCost['International']['Minimum']), Rule::maximum(Product::ShippingCost['International']['Maximum'])],
					'packageWeight' => ['bail', 'nullable', 'numeric', Rule::minimum(Product::Weight['Minimum']), Rule::maximum(Product::Weight['Maximum'])],
					'packageLength' => ['bail', 'nullable', 'numeric', Rule::minimum(Product::Dimensions['Length']['Minimum']), Rule::maximum(Product::Dimensions['Length']['Maximum'])],
					'packageBreadth' => ['bail', 'nullable', 'numeric', Rule::minimum(Product::Dimensions['Breadth']['Minimum']), Rule::maximum(Product::Dimensions['Breadth']['Maximum'])],
					'packageHeight' => ['bail', 'nullable', 'numeric', Rule::minimum(Product::Dimensions['Height']['Minimum']), Rule::maximum(Product::Dimensions['Height']['Maximum'])],
					'domesticWarranty' => ['bail', 'nullable', 'numeric', Rule::minimum(Product::Warranty['Domestic']['Minimum']), Rule::maximum(Product::Warranty['Domestic']['Maximum'])],
					'internationalWarranty' => ['bail', 'nullable', 'numeric', Rule::minimum(Product::Warranty['International']['Minimum']), Rule::maximum(Product::Warranty['International']['Maximum'])],
					'warrantySummary' => ['bail', 'nullable', 'string', 'min:1', 'max:100000'],
					'warrantyServiceType' => ['bail', 'nullable', 'string', Rule::in([WarrantyServiceType::OnSite, WarrantyServiceType::WalkIn])],
					'coveredInWarranty' => ['bail', 'nullable', 'string', 'min:1', 'max:100000'],
					'notCoveredInWarranty' => ['bail', 'nullable', 'string', 'min:1', 'max:100000'],
					'maxQuantityPerOrder' => ['bail', 'nullable', 'numeric', 'min:1', 'max:1000'],
					'primaryImageIndex' => ['bail', 'required', 'numeric', Rule::minimum(0), Rule::maximum(8)],
					'files' => ['bail', 'nullable', 'min:1', 'max:8'],
					'files.*' => ['bail', 'nullable', 'mimes:jpg,jpeg,png,bmp', 'min:1', 'max:5120'],
					'attributes' => ['bail', 'required'],
					'attributes.*.key' => ['bail', 'required', 'exists:attributes,id'],
					'attributes.*.value' => ['bail', 'required'],
				],
				'trailer' => [
					'video' => ['bail', 'required', 'mimes:mp4', 'min:1', 'max:100000'],
				],
			],
		];
	}

	protected function storeProduct(array $payload): Product{
		return Product::create($payload);
	}

	protected function storeAttribute(Product $product, array $payload): Collection{
		if (!$this->items) {
			$this->items = $product->category->attributeSet->items;
		}
		$attributesCollection = new Collection();
		collect($payload)->each(function ($payload) use ($product, $attributesCollection){
			$attribute = Attribute::retrieve($payload['key']);
			$group = $this->items->where('attributeId', $attribute->id())->pluck('group')->first();
			$value = $payload['value'];
			$created = null;
			if (Arrays::isArray($value)) {
				$created = $product->attributes()->create([
					'attributeId' => $attribute->id(),
					'variantAttribute' => $attribute->useToCreateVariants(),
					'showInCatalogListing' => $attribute->showInCatalogListing(),
					'visibleToCustomers' => $attribute->visibleToCustomers(),
					'label' => $attribute->name(),
					'group' => $group,
					'value' => $attribute->combineMultipleValues() ? Str::join(Str::WhiteSpace, $value) : $value,
				]);
			}
			else {
				$created = $product->attributes()->create([
					'attributeId' => $attribute->id(),
					'variantAttribute' => $attribute->useToCreateVariants(),
					'showInCatalogListing' => $attribute->showInCatalogListing(),
					'visibleToCustomers' => $attribute->visibleToCustomers(),
					'label' => $attribute->name(),
					'group' => $group,
					'value' => $value,
				]);
			}
			$attributesCollection->push($created);
		});
		return $attributesCollection;
	}

	protected function storeImages(Product $product, array $payload): Collection{
		$images = new Collection();
		foreach ($payload as $image) {
			$images->push($product->images()->create([
				'path' => $image,
				'tag' => $product->sku(),
			]));
		}
		return $images;
	}

	protected function category(): Category{
		return Category::retrieve(request('categoryId'));
	}

	protected function brand(): Brand{
		return Brand::retrieve(request('brandId'));
	}

	protected function isInvalidCategory(Category $category): bool{
		return !Str::equals($category->type(), Category::Types['Vertical']);
	}

	protected function isBrandApprovedForSeller(Brand $brand){
		return Brand::startQuery()->seller($this->guard()->id())->displayable()->key($brand->id())->first() !== null;
	}

	protected function storeTrailer(UploadedFile $file): ?string{
		return SecuredDisk::access()->putFile(Directories::Trailers, $file);
	}

	protected function isVariantType(): bool{
		return request('type') == Product::Type['Variant'];
	}

	protected function calculateDiscount(int $originalPrice, int $sellingPrice): int{
		$difference = $originalPrice - $sellingPrice;
		if ($difference == 0)
			return 0;
		else {
			return intval(($difference * 100.0) / $originalPrice);
		}
	}

	protected function validateOuter(): array{
		return $this->requestValid(request(), $this->rules['store']['outer']);
	}

	protected function validateProductPayload(array $payload): array{
		return $this->arrayValid($payload, $this->rules['store']['product']);
	}

	protected function validateAttributePayload(array $payload): array{
		return $this->arrayValid($payload, $this->rules['store']['attribute']);
	}

	protected function validateTrailerPayload(array $payload): array{
		return $this->arrayValid($payload, $this->rules['store']['trailer']);
	}

	protected function sessionUuid(): string{
		return Str::makeUuid();
	}

	protected function convertAllSimpleToVariants(string $token){
		$products = Product::startQuery()->seller($this->guard()->id())->simple()->group($token)->get();
		// If there are more than one products under the same group,
		// it means they should be treated as variants of the same kind.
		// Otherwise let the be the type they are.
		if ($products->count() > 1) {
			$products->each(function (Product $product){
				$product->type(Product::Type['Variant']);
				$product->save();
			});
			return true;
		}
		else {
			return false;
		}
	}

	protected function validateToken(){
		$productToken = ProductToken::where([
			['token', request()->header('X-PRODUCT-TOKEN')],
			['sellerId', $this->guard()->id()],
			['createdFor', request()->ip()],
			['validUntil', '>', Time::mysqlStamp()],
		])->first();
		if ($productToken == null)
			throw new TokenInvalidException();
		else
			return $productToken->token();
	}

	protected function guard(){
		return auth('seller-api');
	}
}