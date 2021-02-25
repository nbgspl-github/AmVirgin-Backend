<?php

namespace App\Http\Modules\Customer\Controllers\Api\Shop;

use App\Library\Utils\Extensions\Arrays;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class FilterController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
    public function __construct ()
    {
        parent::__construct();
    }

    public function show (\App\Models\Category $category): JsonResponse
    {
        $products = Product::startQuery()->displayable()->categoryOrDescendant($category->id)->withRelations(['brand:id,name', 'category:id,name', 'originalAttributes']);
        $collection = $products->get();
        $payload = new \Illuminate\Support\Collection();
        $payload->push($this->price($collection));
        $payload->push($this->discount($collection));
        $payload->push($this->brand($collection));
        $payload->push($this->category($collection));
        $payload->push($this->color($collection));
        return responseApp()->prepare(
            $payload->filter()->values()->toArray()
        );
    }

    protected function price (\Illuminate\Support\Collection $values): array
    {
        $priceCollection = $values->pluck('originalPrice');
        $minimumPrice = $priceCollection->min();
        $maximumPrice = $priceCollection->max();
        $itemCount = $priceCollection->count();
        if ($itemCount == 0) {
            // If there are no products, just create a 0 - 0 range filter.
            return [
                'upper' => 0,
                'lower' => 0,
                'count' => 0,
            ];
        }
        $boundaries = config('filters.price.boundaries');
        $divisions = -1;

        // Find the highest boundary value by comparing maxPrice.
        foreach ($boundaries as $key => $value) {
            if ($key >= $maximumPrice) {
                $divisions = $value;
                break;
            }
        }

        // If the threshold value wasn't matched, means the maximum price has
        // exceeded the defined threshold limit. Hence we revert to default divisions.
        if ($divisions == -1) {
            $divisions = config('filters.price.static.divisions');
        }

        // Now we can calculate a median value, upon which we'll create price segments.
        // We must also ensure to divide even by even only. If that's not the case, we'll add 1 to all ranges.
        $diff = $maximumPrice - $minimumPrice;
        is_even($diff) && is_even($divisions) ? $neutralizer = 0 : $neutralizer = 1;
        $median = (int)($diff / $divisions);

        $sections = Arrays::Empty;
        for ($i = 0; $i < $divisions; $i++) {
            $lastMinimum = $minimumPrice;
            $minimumPrice = $minimumPrice + $median + $neutralizer;
            $productCount = $priceCollection->whereBetween(null, [$lastMinimum, $minimumPrice])->count();
            if ($productCount > 0) {
                Arrays::push($sections, [
                    'upper' => $lastMinimum,
                    'lower' => $minimumPrice,
                    'count' => $productCount,
                ]);
            }
        }
        return [
            'key' => 'filter_price',
            'label' => 'Price',
            'type' => 'price',
            'mode' => 'multiple',
            'options' => $sections,
        ];
    }

    protected function brand (\Illuminate\Support\Collection $values): array
    {
        $brands = $values->unique('brandId')->transform(function ($item) {
            if ($item['brand'] != null) {
                return [
                    'key' => $item['brand']['id'],
                    'name' => $item['brand']['name'],
                ];
            }
            return null;
        });
        $brands = $brands->filter()->values();
        return [
            'key' => 'filter_brand',
            'label' => 'Brand',
            'type' => 'brand',
            'mode' => 'multiple',
            'options' => $brands,
        ];
    }

    protected function discount (\Illuminate\Support\Collection $collection): array
    {
        $values = $collection->pluck('discount');
        $discountCollection = $values;
        $maxDiscount = $values->max();
        $divisions = Arrays::Empty;
        for ($tenths = 10; $tenths <= 90; $tenths += 10) {
            $itemsInRange = $discountCollection->whereBetween(null, [$tenths, $maxDiscount])->count();
            if ($itemsInRange > 0) {
                Arrays::push($divisions, [
                    'limit' => $tenths,
                    'count' => $itemsInRange,
                ]);
            }
        }
        return [
            'key' => 'filter_discount',
            'label' => 'Discount',
            'type' => 'discount',
            'mode' => 'single',
            'options' => $divisions,
        ];
    }

    protected function category (\Illuminate\Support\Collection $collection): ?array
    {
        return null;
    }

    protected function color (\Illuminate\Support\Collection $collection): ?array
    {
//		$colors = new \Illuminate\Support\Collection();
//		$collection->filter(function (Product $product) {
//			return $product->originalAttributes->filter(function (\App\Models\Attribute $attribute) {
//				return $attribute->code == 'color';
//			});
//		});
//		$colors = $collection->unique('brandId')->transform(function ($item) {
//			return [
//				'key' => $item['brand']['id'],
//				'name' => $item['brand']['name'],
//			];
//		});
//		return [
//			'key' => 'filter_brand',
//			'label' => 'Brand',
//			'type' => 'brand',
//			'mode' => 'multiple',
//			'options' => $colors,
//		];
        return null;
    }

    protected function gender (\Illuminate\Support\Collection $collection): array
    {
        return [
            ['key' => 'male', 'name' => 'Men'],
            ['key' => 'female', 'name' => 'Women'],
            ['key' => 'female', 'name' => 'Boys'],
            ['key' => 'female', 'name' => 'Girls'],
        ];
    }
}