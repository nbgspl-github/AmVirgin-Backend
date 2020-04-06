<?php

namespace App\Http\Controllers\App\Customer;

use App\Classes\Arrays;
use App\Classes\Rule;
use App\Classes\Sorting\AbstractSorts;
use App\Classes\Sorting\DiscountDescending;
use App\Classes\Sorting\Relevance;
use App\Classes\Sorting\Sorts;
use App\Classes\Sorting\WhatIsNew;
use App\Classes\Sorting\Popularity;
use App\Classes\Sorting\PriceAscending;
use App\Classes\Sorting\PriceDescending;
use App\Classes\Str;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Interfaces\Tables;
use App\Models\CatalogFilter;
use App\Models\Category;
use App\Models\Product;
use App\Queries\ProductQuery;
use App\Resources\Products\Customer\CatalogListResource;
use App\Resources\Products\Customer\SimpleProductResource;
use App\Resources\Products\Customer\VariantProductResource;
use App\Resources\Shop\Customer\Catalog\Filters\AbstractBuiltInResource;
use App\Resources\Shop\Customer\Catalog\Filters\BrandResource;
use App\Resources\Shop\Customer\Catalog\Filters\CategoryResource;
use App\Resources\Shop\Customer\Catalog\Filters\DiscountResource;
use App\Resources\Shop\Customer\Catalog\Filters\FilterResource;
use App\Resources\Shop\Customer\Catalog\Filters\GenderResource;
use App\Resources\Shop\Customer\Catalog\Filters\PriceRangeResource;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Throwable;

class CatalogController extends ExtendedResourceController{
	use ValidatesRequest;
	protected const DefaultSort = 'relevance';
	protected const ItemsPerPage = 50;
	protected array $rules = [];

	public function __construct(){
		parent::__construct();
		$this->rules = [
			'index' => [
				'category' => ['bail', 'required', Rule::existsPrimary(Tables::Categories)->whereNot('type', Category::Types['Root'])],
				'sortBy' => ['bail', 'nullable', Rule::in(collect(config('sorts.shop'))->keys()->toArray())],
				'page' => ['bail', 'nullable', 'numeric', 'min:1', 'max:10000'],
			],
		];
	}

	public function index(): JsonResponse{
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules['index']);
			$itemsPerPage = config('shop.catalog.defaults.listing.perPage', 50);
			if (!isset($validated['page'])) $validated['page'] = 0;
			if (!isset($validated['sortBy'])) $validated['sortBy'] = config('shop.catalog.defaults.sort', 'relevance');

			// Grab the sorting algorithm.
			$sort = AbstractSorts::take(config('sorts.shop')[$validated['sortBy']]['class']);

			// Collect all viable products.
			$products = Product::startQuery()->displayable()->categoryOrDescendant($validated['category'])->singleVariantMode();

			// Preload all relations that we'll need in this call.
			$products->withRelations('options', 'brand');

			// Apply any incoming sort request, or just go with the default one.
			$sort::sort($products);

			// Paginate to first 50 results for page.
			$total = $products->count();
			$products = $products->paginate($itemsPerPage);
			$products = CatalogListResource::collection($products);
			$meta = [
				'pagination' => [
					'pages' => countRequiredPages($total, $itemsPerPage),
					'items' => ['total' => $total, 'chunk' => $itemsPerPage],
				],
			];
			$response->status(HttpOkay)->message('Listing products for given category.')
				->setValue('meta', $meta)
				->setValue('payload', $products);
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
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
			$product = Product::startQuery()->displayable()->key($id)->firstOrFail();
			if (Str::equals($product->type(), Product::Type['Variant'])) {
				$product = new VariantProductResource($product);
			}
			else {
				$product = new SimpleProductResource($product);
			}
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

	protected function guard(){
		return auth(self::CustomerAPI);
	}
}