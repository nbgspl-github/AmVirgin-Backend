<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\BaseController;
use App\Models\Currency;
use App\Traits\FluentResponse;

class CurrencyController extends BaseController
{
	use FluentResponse;

	public function __construct ()
	{
		parent::__construct();
	}

	public function index ()
	{
		$currency = Currency::all();
		$currency->transform(function (Currency $currency) {
			return $currency->code;
		});
		return $this->responseApp()->status(\Illuminate\Http\Response::HTTP_OK)->setValue('data', $currency)->message(function () use ($currency) {
			return sprintf('Found %d currencies.', $currency->count());
		})->send();
	}
}