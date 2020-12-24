<?php

namespace App\Http\Modules\Seller\Controllers\Api;

use App\Http\Modules\Shared\Controllers\BaseController;
use App\Models\Currency;
use App\Traits\FluentResponse;

class CurrencyController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
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
		return responseApp()->status(\Illuminate\Http\Response::HTTP_OK)->setValue('data', $currency)->message(function () use ($currency) {
			return sprintf('Found %d currencies.', $currency->count());
		})->send();
	}
}