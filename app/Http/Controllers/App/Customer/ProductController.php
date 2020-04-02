<?php

namespace App\Http\Controllers\App\Customer;

use App\Classes\Rule;
use App\Classes\Sorting\DiscountDescending;
use App\Classes\Sorting\Natural;
use App\Classes\Sorting\NewlyAdded;
use App\Classes\Sorting\Popularity;
use App\Classes\Sorting\PriceAscending;
use App\Classes\Sorting\PriceDescending;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Interfaces\Tables;
use App\Models\Product;
use App\Resources\Products\Customer\ProductResource;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Throwable;

class ProductController extends ExtendedResourceController{
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
				'categoryId' => ['bail', 'required', Rule::existsPrimary(Tables::Categories)],
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
			$products = $products->orderBy($algorithm[0], $algorithm[1])->paginate(50);
			$products = ProductResource::collection($products);
			$response->status(HttpOkay)->message('Listing available products for given category.')->setValue('meta', ['total' => $totalInCategory, 'pageCount' => countRequiredPages($totalInCategory, self::ItemsPerPage)])->setValue('data', $products);
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

	public function sortsIndex(){
		$sorts = collect(self::SortingOptions);
		$sorts->transform(function ($item){
			unset($item['algorithm']);
			return $item;
		});
		return responseApp()->status(HttpOkay)->message(function () use ($sorts){
			return sprintf('There are a total of %d sorting options available.', $sorts->count());
		})->setValue('data', $sorts)->send();
	}

	public function show($id){
		$response = responseApp();
		try {
			$product = Product::where([
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

	protected function guard(){
		return auth('customer-api');
	}
}