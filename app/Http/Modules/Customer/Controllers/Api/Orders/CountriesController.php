<?php

namespace App\Http\Modules\Customer\Controllers\Api\Orders;

use App\Models\Country;

class CountriesController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index () : \Illuminate\Http\JsonResponse
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