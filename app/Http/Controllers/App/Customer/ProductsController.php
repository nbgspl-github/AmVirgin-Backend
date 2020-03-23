<?php

namespace App\Http\Controllers\App\Customer;

use App\Classes\Sorting\DiscountDescending;
use App\Classes\Sorting\Natural;
use App\Classes\Sorting\NewlyAdded;
use App\Classes\Sorting\Popularity;
use App\Classes\Sorting\PriceAscending;
use App\Classes\Sorting\PriceDescending;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\Product;
use App\Resources\Products\Customer\ProductResource;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class ProductsController extends ExtendedResourceController {
	use ValidatesRequest;

	protected string $defaultSort = 'relevance';

	protected int $resultsPerPage = 50;

	protected array $sortingOptions = [
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

	protected array $rules = [
		'index' => [
			'sortKey' => ['bail', 'nullable', 'string', 'min:1', 'max:50'],
			'page' => ['bail', 'nullable', 'numeric', 'min:1', 'max:10000'],
		],
	];

	public function index() {
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules['index']);
			if (!isset($validated['page'])) $validated['page'] = 0;
			if (!isset($validated['sortKey'])) $validated['sortKey'] = $this->defaultSort;
			$sorts = collect($this->sortingOptions);
			$chosenSort = $sorts->firstWhere('key', $validated['sortKey']);
			$algorithm = $chosenSort['algorithm']::obtain();
			$products = Product::where([
				['categoryId', request('categoryId')],
				['draft', false],
				['deleted', false],
				['visibility', true],
			]);
			$totalInCategory = $products->count('id');
			$products = $products->orderBy($algorithm[0], $algorithm[1])->paginate(50);
			$products = ProductResource::collection($products);
			$response->status(HttpOkay)->message(function () use ($totalInCategory) {
				return sprintf('Found %d products under that category.', $totalInCategory);
			})->setValue('meta', ['total' => $totalInCategory, 'pageCount' => $this->countRequiredPages($totalInCategory, $this->resultsPerPage)])->setValue('data', $products);
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function sortsIndex() {
		$sorts = collect($this->sortingOptions);
		$sorts->transform(function ($item) {
			unset($item['algorithm']);
			return $item;
		});
		return responseApp()->status(HttpOkay)->message(function () use ($sorts) {
			return sprintf('There are a total of %d sorting options available.', $sorts->count());
		})->setValue('data', $sorts)->send();
	}

	public function show($id) {
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

	protected function guard() {
		return auth('customer-api');
	}

	protected function countRequiredPages(int $total, int $perPage) {
		if ($total <= $perPage)
			return 1;

		$result = $total / $perPage;
		$remainder = $total % $perPage;
		if ($remainder > 0 && $remainder <= $perPage) {
			$result += 1;
		}
		return $result;
	}
}