<?php

namespace App\Http\Controllers\Web\Admin\Shop;

use App\Classes\Str;
use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Models\Settings;
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
					'duration' => [''],
				],
			],
		];
	}

	public function choices() {
		return view('admin.shop.home-page.choices');
	}

	public function editSaleOfferTimerDetails() {
		$details = Settings::get('shopSaleOfferDetails', null);
		$saleOffer = null;
		if ($details == null) {
			$saleOffer = [
				'title' => Str::Empty,
				'statements' => [],
				'countDown' => '00:01:00',
			];
		}
		else {
			$saleOffer = jsonDecodeArray($details);
		}
		return view('admin.shop.home-page.sale-offer-timer.edit')->with('payload', (object)$saleOffer);
	}

	public function updateSaleOfferTimerDetails() {
		$response = responseWeb();
		try {
			$validated = $this->requestValid(request(), $this->rules['update']['saleOfferTime']);

		}
		catch (ValidationException $exception) {

		}
		catch (Throwable $exception) {

		}
		finally {

		}
	}
}