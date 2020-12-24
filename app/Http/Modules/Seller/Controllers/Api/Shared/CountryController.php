<?php

namespace App\Http\Modules\Seller\Controllers\Api\Shared;

use App\Models\Country;
use App\Traits\FluentResponse;

class CountryController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
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
		return responseApp()->status(\Illuminate\Http\Response::HTTP_OK)->setValue('data', $categories)->message(function () use ($categories) {
			return sprintf('Found %d countries.', $categories->count());
		})->send();
	}
}