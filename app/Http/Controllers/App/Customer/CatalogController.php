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
use App\Models\Category;
use App\Models\Product;
use App\Resources\Products\Customer\SimpleProductResource;
use App\Resources\Products\Customer\VariantProductResource;
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
	protected const PriceBreakpoints = [
		[0, 1000, 1],
		[1000, 5000, 2],
		[5000, 10000, 2],
		[10000, 15000, 2],
		[15000, 20000, 2],
		[20000, 25000, 3],
		[30000, 35000, 3],
		[35000, 40000, 4],
		[40000, 50000, 5],
		[40000, 50000, 5],
	];
	protected const MinimumIndex = 0;
	protected const MaximumIndex = 1;
	protected const DivisionsIndex = 2;
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
			$products = Product::startQuery()->displayable()->categoryOrDescendant(request('categoryId'));
			$totalInCategory = $products->count('id');
			$products = $products->orderBy($algorithm[0], $algorithm[1])->paginate(self::ItemsPerPage);
			$products = SimpleProductResource::collection($products);
			$meta = ['total' => $totalInCategory, 'pageCount' => countRequiredPages($totalInCategory, self::ItemsPerPage)];
			$filters = $this->filters($validated['categoryId']);
			$response->status(HttpOkay)->message('Listing available products for given category.')
				->setValue('meta', $meta)
				->setValue('filters', $filters)
				->setValue('data', $products);
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

	public function filters(int $categoryId): array{
		$filters = new Collection();

		/**
		 * Generating price segment based on categories' min and max priced product.
		 */
		$segments = Arrays::Empty;
		$query = Product::startQuery()->categoryOrDescendant($categoryId);
		$min = $query->min('originalPrice');
		$max = $query->max('originalPrice');
		$divisions = 1;
		foreach (self::PriceBreakpoints as $breakpoint) {
			if ($min >= $breakpoint[0] && $max < $breakpoint[1]) {
				$divisions = $breakpoint[2];
				break;
			}
		}
		return [];
	}

	public static function segments(int $min, int $max, array $breakpoint): array{
		$segments = Arrays::Empty;
		$actualMin = $min;
		$actualMax = $max;
		$divisions = $breakpoint[self::DivisionsIndex];
		$difference = $actualMax - $actualMin;
		$median = round((float)$difference / (float)$divisions);
		for ($count = 0; $count < $divisions; $count++) {
			$segments[] = [
				'start' => $actualMin,
				'end' => $actualMin + $median,
			];
			$actualMin += $median;
		}
		return $segments;
	}

	protected function guard(){
		return auth('customer-api');
	}
}