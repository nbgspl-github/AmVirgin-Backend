<?php

namespace App\Http\Controllers\App\Seller;

use App\Http\Controllers\BaseController;
use App\Models\Country;
use App\Traits\FluentResponse;

class CountryController extends BaseController
{
	use FluentResponse;

	public function __construct ()
	{
		parent::__construct();
	}

	public function index ()
	{
		$categories = Country::all();
		$categories->transform(function (Country $country) {
			return [
				'id' => $country->getKey(),
				'name' => $country->name,
				'initials' => $country->initials,
			];
		});
		return $this->response()->status(HttpOkay)->setValue('data', $categories)->message(function () use ($categories) {
			return sprintf('Found %d countries.', $categories->count());
		})->send();
	}
}