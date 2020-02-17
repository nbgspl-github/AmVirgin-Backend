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
use App\Resources\Products\Seller\ProductResource;
use App\Traits\ValidatesRequest;
use Throwable;

class ProductsController extends ExtendedResourceController{
	use ValidatesRequest;

	protected $defaultSort = 'relevance';

	protected $sortingOptions = [
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

	protected $rules = [
		'index' => [
			'sortKey' => ['bail', 'nullable', 'string', 'min:1', 'max:50'],
			'offset' => ['bail', 'nullable', 'numeric', 'min:0', 'max:1000'],
			'limit' => ['bail', 'nullable', 'numeric', 'min:1', 'max:1000'],
		],
	];

	public function index(){
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules['index']);
			if (!isset($validated['offset'])) $validated['offset'] = 0;
			if (!isset($validated['limit'])) $validated['limit'] = 1;
			if (!isset($validated['sortKey'])) $validated['sortKey'] = $this->defaultSort;
			$sorts = collect($this->sortingOptions);
			$chosenSort = $sorts->firstWhere('key', $validated['sortKey']);
			$algorithm = $chosenSort['algorithm']::obtain();
			$products = Product::where('categoryId', request('categoryId'))
				->offset($validated['offset'])
				->limit($validated['limit'])
				->orderBy($algorithm[0], $algorithm[1])
				->get();

			$products = ProductResource::collection($products);
			$response->status(HttpOkay)->message(function () use ($products){
				return sprintf('Found %d products under that category.', count($products));
			})->setValue('data', $products);
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