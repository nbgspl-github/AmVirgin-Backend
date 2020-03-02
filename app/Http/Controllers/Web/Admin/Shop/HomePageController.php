<?php

namespace App\Http\Controllers\Web\Admin\Shop;

use App\Classes\Str;
use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Models\Category;
use App\Models\Settings;
use App\Storage\SecuredDisk;
use App\Traits\ValidatesRequest;
use Throwable;

class HomePageController extends BaseController {
	use ValidatesRequest;
	protected array $rules;

	public function __construct() {
		parent::__construct();
		$this->rules = [
			'update' => [
				'saleOfferTime' => [
					'title' => ['bail', 'required', 'string', 'min:1', 'max:50'],
					'countDown' => ['bail', 'required', 'date_format:H:i:s'],
					'statements' => ['bail', 'required', 'string'],
				],
				'brandsInFocus' => [
					'focus.*' => ['bail', 'required', 'distinct'],
				],
			],
		];
	}

	public function choices() {
		return view('admin.shop.choices');
	}

	public function editSaleOfferTimerDetails() {
		$details = Settings::get('shopSaleOfferDetails', null);
		$saleOffer = null;
		if ($details == null) {
			$saleOffer = [
				'title' => Str::Empty,
				'statements' => [],
				'countDown' => '00:01:00',
				'visible' => true,
			];
		}
		else {
			$saleOffer = jsonDecodeArray($details);
			$saleOffer['statements'] = implode(Str::NewLine, $saleOffer['statements']);
		}
		return view('admin.shop.sale-offer-timer.edit')->with('payload', (object)$saleOffer);
	}

	public function updateSaleOfferTimerDetails() {
		$response = responseWeb();
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['update']['saleOfferTime']);
			$saleOffer = [
				'title' => $validated->title,
				'statements' => explode(Str::NewLine, $validated->statements),
				'countDown' => $validated->countDown,
				'visible' => request()->has('visible'),
			];
			Settings::set('shopSaleOfferDetails', jsonEncode($saleOffer));
			$response->success('Sale offer timer details updated successfully.')->route('admin.shop.choices');
		}
		catch (ValidationException $exception) {
			$response->error($exception->getMessage())->data(request()->all())->back();
		}
		catch (Throwable $exception) {
			$response->error($exception->getMessage())->back()->data(request()->all());
		}
		finally {
			return $response->send();
		}
	}

	public function editBrandsInFocus() {
		$categories = $topLevel = Category::where('parentId', 0)->get();
		$topLevel->transform(function (Category $topLevel) {
			$children = $topLevel->children()->get();
			$children = $children->transform(function (Category $child) {
				$innerChildren = $child->children()->get();
				$innerChildren = $innerChildren->transform(function (Category $inner) {
					return [
						'id' => $inner->getKey(),
						'name' => $inner->getName(),
					];
				});
				return [
					'id' => $child->getKey(),
					'name' => $child->getName(),
					'hasInner' => $innerChildren->count() > 0,
					'inner' => $innerChildren,
				];
			});
			return [
				'id' => $topLevel->getKey(),
				'name' => $topLevel->getName(),
				'hasInner' => $children->count() > 0,
				'inner' => $children,
			];
		});
		return view('admin.shop.brands-in-focus.edit')->with('categories', $topLevel);
	}

	public function updateBrandsInFocus() {
		$response = responseWeb();
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['update']['brandsInFocus']);

		}
		catch (ValidationException $exception) {
			$response->error('Each item must have a different category.')->data(request()->all());
		}
		catch (Throwable $exception) {
			$response->error($exception->getMessage())->data(request()->all());
		}
		finally {
			return $response->send();
		}
	}
}