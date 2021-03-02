<?php

namespace App\Http\Modules\Customer\Controllers\Api\Shop;

use App\Classes\Sorting\AbstractSorts;
use App\Http\Modules\Customer\Requests\Shop\Catalog\IndexRequest;
use App\Models\Category;
use App\Models\Product;
use App\Resources\Products\Customer\CatalogListResource;
use App\Resources\Products\Customer\ProductResource;
use Illuminate\Http\JsonResponse;

class CatalogController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
    protected const DEFAULT_SORT = 'relevance';
    protected const PER_PAGE = 50;

    public function __construct ()
    {
        parent::__construct();
    }

    public function index (IndexRequest $request, Category $category): JsonResponse
    {
        // Grab the sorting algorithm.
        $sort = AbstractSorts::take(config('sorts.shop')[$request->sortBy ?? self::DEFAULT_SORT]['class']);

        // Collect all viable products.
        $products = Product::startQuery()->displayable()->categoryOrDescendant($category->id)->singleVariantMode();

        // Preload all relations that we'll need in this call.
        $products->withRelations(['options', 'brand']);

        // Apply any incoming sort request, or just go with the default one.
        $sort::sort($products);

        // Apply a range of incoming filters where they're applicable
        $filters = $products->applyFilters($request, true);

        return responseApp()->prepare(
            CatalogListResource::collection($products->paginate($this->paginationChunk(self::PER_PAGE)))
                ->additional(['filters' => $filters])->response()->getData()
        );
    }

    public function show (Product $product): JsonResponse
    {
        return responseApp()->prepare(
            new ProductResource($product), \Illuminate\Http\Response::HTTP_OK, 'Listing product details.'
        );
    }
}