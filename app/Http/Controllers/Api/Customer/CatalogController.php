<?php

namespace App\Http\Controllers\Api\Customer;

use App\Classes\Sorting\AbstractSorts;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\ApiController;
use App\Library\Enums\Common\Tables;
use App\Library\Utils\Extensions\Rule;
use App\Models\Category;
use App\Models\Product;
use App\Resources\Products\Customer\CatalogListResource;
use App\Resources\Products\Customer\ProductResource;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Throwable;

class CatalogController extends ApiController
{
	use ValidatesRequest;

	protected const DefaultSort = 'relevance';
	protected const ItemsPerPage = 50;
	protected array $rules = [];

	public function __construct ()
	{
		parent::__construct();
		$this->rules = [
			'index' => [
				'category' => ['bail', 'required', Rule::existsPrimary(Tables::Categories)->whereNot('type', Category::Types['Root'])],
				'sortBy' => ['bail', 'nullable', Rule::in(collect(config('sorts.shop'))->keys()->toArray())],
				'page' => ['bail', 'nullable', 'numeric', 'min:1', 'max:10000'],
			],
		];
	}

	public function index (): JsonResponse
	{
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
			$total = $products->get('name')->count();
			$products = $products->paginate($itemsPerPage);
			$products = CatalogListResource::collection($products);
			$meta = [
				'pagination' => [
					'pages' => countRequiredPages($total, $itemsPerPage),
					'items' => ['total' => $total, 'chunk' => $itemsPerPage],
				],
			];
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Listing products for given category.')
				->setValue('meta', $meta)
				->setValue('payload', $products);
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function show ($id): JsonResponse
	{
		$response = responseApp();
		try {
			$product = Product::startQuery()->displayable()->key($id)->firstOrFail();
			$product = new ProductResource($product);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Listing details of product.')->setValue('payload', $product);
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find the product for that key.');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}