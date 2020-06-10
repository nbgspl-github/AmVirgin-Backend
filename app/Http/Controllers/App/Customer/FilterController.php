<?php

namespace App\Http\Controllers\App\Customer;

use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\CatalogFilter;
use App\Models\Product;
use App\Queries\ProductQuery;
use App\Resources\Shop\Customer\Catalog\Filters\AbstractBuiltInResource;
use App\Resources\Shop\Customer\Catalog\Filters\BrandResource;
use App\Resources\Shop\Customer\Catalog\Filters\CategoryResource;
use App\Resources\Shop\Customer\Catalog\Filters\DiscountResource;
use App\Resources\Shop\Customer\Catalog\Filters\FilterResource;
use App\Resources\Shop\Customer\Catalog\Filters\GenderResource;
use App\Resources\Shop\Customer\Catalog\Filters\PriceRangeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class FilterController extends ExtendedResourceController{
	public function __construct(){
		parent::__construct();
	}

	public function show(): JsonResponse{
		$id = request('category');
		$filters = CatalogFilter::startQuery()->category($id)->get();
		$products = Product::startQuery()->displayable()->categoryOrDescendant($id);

		// Get all required columns.
		$requiredColumns = collect([
			PriceRangeResource::RequiredColumn,
			BrandResource::RequiredColumn,
			GenderResource::RequiredColumn,
			CategoryResource::RequiredColumn,
			DiscountResource::RequiredColumn,
		])->flatten()->values()->toArray();

		// Pre-fetch all values from all essential columns for built-in filters.
		$requiredColumnValues = $products->get($requiredColumns);

		// Transform each available filters, excluding inbuilt ones by send them to a new function.
		$filters->transform(function (CatalogFilter $catalogFilter) use ($requiredColumnValues){
			return $catalogFilter->builtIn() ? $this->transform($catalogFilter, $requiredColumnValues) : new FilterResource($catalogFilter);
		});

		// Prepare and send response.
		return responseApp()
			->status(HttpOkay)->setValue('payload', $filters)
			->message('Listing available filters for category.')->send();
	}

	public function transform(CatalogFilter $catalogFilter, Collection $columnValues): AbstractBuiltInResource{
		// Retrieve the appropriate Resource class for built in filter.
		$resourceClass = CatalogFilter::BuiltInFilterResourceMapping[$catalogFilter->builtInType()];
		// Get column values as per the requirements of resource class.
		$values = $columnValues->pluck($resourceClass::RequiredColumn);
		// Convert the resource to array instance.
		return (new $resourceClass($catalogFilter))->withValues($values);
	}

	protected function guard(){
		return auth(self::CustomerAPI);
	}
}