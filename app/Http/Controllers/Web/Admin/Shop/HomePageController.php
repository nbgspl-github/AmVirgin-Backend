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
					'countDown' => ['bail', 'required', 'date_format:H:i:s'],
					'statements' => ['bail', 'required', 'string'],
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
}