<?php

namespace App\Http\Controllers\App\Seller;

use App\Http\Controllers\BaseController;
use App\Models\Currency;
use App\Traits\FluentResponse;

class CurrenciesController extends BaseController{
	use FluentResponse;

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$currency = Currency::all();
		$currency->transform(function (Currency $currency){
			return $currency->code;
		});
		return $this->response()->status(HttpOkay)->setValue('data', $currency)->message(function () use ($currency){
			return sprintf('Found %d currencies.', $currency->count());
		})->send();
	}
}