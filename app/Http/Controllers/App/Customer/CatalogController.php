<?php

namespace App\Http\Controllers\App\Customer;

use App\Classes\Arrays;
use App\Classes\Rule;
use App\Classes\Sorting\DiscountDescending;
use App\Classes\Sorting\Natural;
use App\Classes\Sorting\NewlyAdded;
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
	protected const SortingOptions = [
		[
			'name' => 'Relevance',
			'key' => 'relevance',
			'algorithm' => Natural::class,
		],
		[
			'name' => 'Price: High to Low',
			'key' => 'price-high-to-low',
			'algorithm' => PriceDescending::class,
		],
		[
			'name' => 'Price: Low to High',
			'key' => 'price-low-to-high',
			'algorithm' => PriceAscending::class,
		],
		[
			'name' => 'Popularity',
			'key' => 'popularity',
			'algorithm' => Popularity::class,
		],
		[
			'name' => 'What\'s new?',
			'key' => 'whats-new',
			'algorithm' => NewlyAdded::class,
		],
		[
			'name' => 'Better Discount',
			'key' => 'better-discount',
			'algorithm' => DiscountDescending::class,
		],
	];
	protected array $rules = [];

	public function __construct(){
		parent::__construct();
		$this->rules = [
			'index' => [
				'categoryId' => ['bail', 'required', Rule::existsPrimary(Tables::Categories)->whereNot('type', Category::Types['Root'])],
				'sortKey' => ['bail', 'nullable', 'string', 'min:1', 'max:50'],
				'page' => ['bail', 'nullable', 'numeric', 'min:1', 'max:10000'],
			],
		];
	}

	public function index(): JsonResponse{
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules['index']);
			if (!isset($validated['page'])) $validated['page'] = 0;
			if (!isset($validated['sortKey'])) $validated['sortKey'] = self::DefaultSort;
			$sorts = collect(self::SortingOptions);
			$chosenSort = $sorts->firstWhere('key', $validated['sortKey']);
			$algorithm = $chosenSort['algorithm']::obtain();
			$products = Product::startQuery()->displayable()->categoryOrDescendant($validated['categoryId'])->singleVariantMode();
			$filters = $this->filters($validated['categoryId'], $products);
			$total = $products->count('id');
			$products = $products->orderBy($algorithm[0], $algorithm[1])->paginate(self::ItemsPerPage);
			$products = VariantProductResource::collection($products);
			$meta = [
				'pages' => [
					'total' => $total,
					'count' => countRequiredPages($total, self::ItemsPerPage),
				],
				'filters' => $filters,
			];
			$response->status(HttpOkay)->message('Listing available products for given category.')
				->setValue('meta', $meta)
				->setValue('data', $products);
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
		}
		catch (Throwable $exception) {
			dd($exception);
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

	public function sorts(): JsonResponse{
		$sorts = collect(self::SortingOptions);
		$sorts->transform(function ($item){
			unset($item['algorithm']);
			return $item;
		});
		return responseApp()->status(HttpOkay)->message(function () use ($sorts){
			return sprintf('There are a total of %d sorting options available.', $sorts->count());
		})->setValue('data', $sorts)->send();
	}

	public function filters(int $categoryId, ProductQuery $products): Collection{
		$filters = CatalogFilter::startQuery()->category($categoryId)->get();

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
			return $catalogFilter->builtIn() ? $this->builtInFilter($catalogFilter, $requiredColumnValues) : new FilterResource($catalogFilter);
		});
		return $filters;
	}

	public function builtInFilter(CatalogFilter $catalogFilter, Collection $columnValues): AbstractBuiltInResource{
		// Retrieve the appropriate Resource class for built in filter.
		$resourceClass = CatalogFilter::BuiltInFilterResourceMapping[$catalogFilter->builtInType()];
		// Get column values as per the requirements of resource class.
		$values = $columnValues->pluck($resourceClass::RequiredColumn);
		// Convert the resource to array instance.
		return (new $resourceClass($catalogFilter))->withValues($values);
	}

	protected function guard(){
		return auth('customer-api');
	}
}