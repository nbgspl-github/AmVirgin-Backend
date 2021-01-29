<?php

namespace App\Http\Modules\Admin\Controllers\Web\Shop;

use App\Exceptions\ValidationException;
use App\Library\Utils\Extensions\Str;
use App\Models\Category;
use App\Models\Product;
use App\Models\Settings;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class HomePageController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->rules = [
			'update' => [
				'saleOfferTime' => [
					'title' => ['bail', 'required', 'string', 'min:1', 'max:50'],
					'countDown' => ['bail', 'required', 'date_format:H:i:s'],
					'statements' => ['bail', 'required', 'string'],
				],
			],
		];
	}

	public function choices ()
	{
		return view('admin.shop.choices');
	}

	public function editSaleOfferTimerDetails ()
	{
		$details = Settings::get('shopSaleOfferDetails', null);
		$saleOffer = null;
		if ($details == null) {
			$saleOffer = [
				'title' => Str::Empty,
				'statements' => [],
				'countDown' => '00:01:00',
				'visible' => true,
			];
		} else {
			$saleOffer = jsonDecodeArray($details);
			$saleOffer['statements'] = implode(Str::NewLine, $saleOffer['statements']);
		}
		return view('admin.shop.sale-offer-timer.edit')->with('payload', (object)$saleOffer);
	}

	public function updateSaleOfferTimerDetails ()
	{
		$response = responseWeb();
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['update']['saleOfferTime']);
			$saleOffer = [
				'title' => $validated->title,
				'statements' => explode(Str::NewLine, $validated->statements),
				'countDown' => $validated->countDown,
				'visible' => request()->has('visible'),
			];
			Settings::set('shopSaleOfferDetailsUpdated', time());
			Settings::set('shopSaleOfferDetails', jsonEncode($saleOffer));
			$response->success('Sale offer timer details updated successfully.')->route('admin.shop.choices');
		} catch (ValidationException $exception) {
			$response->error($exception->getMessage())->data(request()->all())->back();
		} catch (Throwable $exception) {
			$response->error($exception->getMessage())->back()->data(request()->all());
		} finally {
			return $response->send();
		}
	}

	public function editBrandsInFocus ()
	{
		$categories = $topLevel = Category::startQuery()->isCategory()->get();
		$topLevel->transform(function (Category $topLevel) {
			$children = $topLevel->children()->get();
			$children = $children->transform(function (Category $child) {
				$innerChildren = $child->children()->get();
				$innerChildren = $innerChildren->transform(function (Category $inner) {
					return [
						'id' => $inner->id,
						'name' => $inner->name,
						'brandInFocus' => $inner->brandInFocus(),
					];
				});
				return [
					'id' => $child->id,
					'name' => $child->name,
					'hasInner' => $innerChildren->count() > 0,
					'inner' => $innerChildren,
					'brandInFocus' => $child->brandInFocus(),
				];
			});
			return [
				'id' => $topLevel->id,
				'name' => $topLevel->name,
				'hasInner' => $children->count() > 0,
				'inner' => $children,
				'brandInFocus' => $topLevel->brandInFocus(),
			];
		});
		return view('admin.shop.brands-in-focus.edit')->with('categories', $topLevel);
	}

	public function updateBrandsInFocus ()
	{
		$response = responseWeb();
		try {
			$choices = request('choice');
			if (count($choices) > 8) {
				$response->error('You can select a maximum of 8 categories only.')->back();
			} else {
				Category::all()->each(function (Category $category) use ($choices) {
					if (in_array($category->id, $choices)) {
						$category->brandInFocus(true);
					} else {
						$category->brandInFocus(false);
					}
					$category->save();
				});
				$response->route('admin.shop.choices')->success('Successfully updated categories for brands in focus.');
			}
		} catch (Throwable $exception) {
			$response->error($exception->getMessage())->data(request()->all());
		} finally {
			return $response->send();
		}
	}

	public function editPopularStuff ()
	{
		$categories = $topLevel = Category::startQuery()->isCategory()->get();
		$topLevel->transform(function (Category $topLevel) {
			$children = $topLevel->children()->get();
			$children = $children->transform(function (Category $child) {
				$innerChildren = $child->children()->get();
				$innerChildren = $innerChildren->transform(function (Category $inner) {
					return [
						'id' => $inner->id,
						'name' => $inner->name,
						'popularCategory' => $inner->popularCategory(),
					];
				});
				return [
					'id' => $child->id,
					'name' => $child->name,
					'hasInner' => $innerChildren->count() > 0,
					'inner' => $innerChildren,
					'popularCategory' => $child->popularCategory(),
				];
			});
			return [
				'id' => $topLevel->id,
				'name' => $topLevel->name,
				'hasInner' => $children->count() > 0,
				'inner' => $children,
				'popularCategory' => $topLevel->popularCategory(),
			];
		});
		return view('admin.shop.popular-stuff.edit')->with('categories', $topLevel);
	}

	public function updatePopularStuff ()
	{
		$response = responseWeb();
		try {
			$choices = request('choice');
			if (count($choices) > 5) {
				$response->error('You can select a maximum of 5 categories only.')->back();
			} else {
				Category::all()->each(function (Category $category) use ($choices) {
					if (in_array($category->id, $choices)) {
						$category->popularCategory(true);
					} else {
						$category->popularCategory(false);
					}
					$category->save();
				});
				$response->route('admin.shop.choices')->success('Successfully updated categories for popular stuff.');
			}
		} catch (Throwable $exception) {
			$response->error($exception->getMessage())->data(request()->all());
		} finally {
			return $response->send();
		}
	}

	public function editTrendingNow ()
	{
		$categories = $topLevel = Category::startQuery()->isCategory()->get();
		$topLevel->transform(function (Category $topLevel) {
			$children = $topLevel->children()->get();
			$children = $children->transform(function (Category $child) {
				$innerChildren = $child->children()->get();
				$innerChildren = $innerChildren->transform(function (Category $inner) {
					return [
						'id' => $inner->id,
						'name' => $inner->name,
						'trendingNow' => $inner->trendingNow(),
					];
				});
				return [
					'id' => $child->id,
					'name' => $child->name,
					'hasInner' => $innerChildren->count() > 0,
					'inner' => $innerChildren,
					'trendingNow' => $child->trendingNow(),
				];
			});
			return [
				'id' => $topLevel->id,
				'name' => $topLevel->name,
				'hasInner' => $children->count() > 0,
				'inner' => $children,
				'trendingNow' => $topLevel->trendingNow(),
			];
		});
		return view('admin.shop.trending-now.edit')->with('categories', $topLevel);
	}

	public function updateTrendingNow ()
	{
		$response = responseWeb();
		try {
			$choices = request('choice');
			if (count($choices) > 4) {
				$response->error('You can select a maximum of 4 categories only.')->back();
			} else {
				Category::all()->each(function (Category $category) use ($choices) {
					if (in_array($category->id, $choices)) {
						$category->trendingNow(true);
					} else {
						$category->trendingNow(false);
					}
					$category->save();
				});
				$response->route('admin.shop.choices')->success('Successfully updated categories for trending now.');
			}
		} catch (Throwable $exception) {
			$response->error($exception->getMessage())->data(request()->all());
		} finally {
			return $response->send();
		}
	}

	public function editHotDeals ()
	{
		$products = Product::startQuery()->displayable()->get();
		$products->transform(function (Product $product) {
			return [
				'id' => $product->id,
				'name' => $product->name,
				'hotDeal' => $product->hotDeal(),
			];
		});
		return view('admin.shop.hot-deals.edit')->with('products', $products);
	}

	public function updateHotDeals ()
	{
		$response = responseWeb();
		try {
			$choices = request('choice');
			if (count($choices) > 50) {
				$response->error('You can select a maximum of 50 products only.')->back();
			} else {
				Product::all()->each(function (Product $product) use ($choices) {
					$product->hotDeal(in_array($product->id, $choices));
					$product->save();
				});
				$response->route('admin.shop.choices')->success('Successfully updated products for hot deals.');
			}
		} catch (Throwable $exception) {
			$response->error($exception->getMessage())->data(request()->all());
		} finally {
			return $response->send();
		}
	}

	public function viewProductDetails ($id) : \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		try {
			$product = Product::findOrFail($id);
			$seller = $product->seller;
			$category = $product->category;
			if ($seller == null) {
				$seller = Str::Empty;
			} else {
				$seller = sprintf('%s [%d]', $seller->name, $seller->getKey());
			}
			if ($category == null) {
				$category = Str::Empty;
			} else {
				$category = sprintf('%s [%d]', $category->name, $category->getKey());
			}
			$data = [
				'name' => $product->name,
				'category' => $category,
				'seller' => $seller,
				'price' => $product->originalPrice . ' ' . $product->currency,
				'stock' => $product->stock,
				'sku' => $product->sku,
			];
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Product details retrieved successfully.')->setValue('data', $data);
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}