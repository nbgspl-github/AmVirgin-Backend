<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\ApiController;
use App\Models\City;

class CitiesController extends ApiController
{
	public function index ($stateId)
	{
		$cities = City::where('stateId', $stateId)->get();
		$cities->transform(function (City $city) {
			return [
				'id' => $city->getKey(),
				'name' => $city->name,
			];
		});
		return responseApp()->status(\Illuminate\Http\Response::HTTP_OK)->message(function () use ($cities) {
			return sprintf('Found %d cities for the given state.', $cities->count());
		})->setValue('data', $cities)->send();
	}

	protected function guard ()
	{
		return auth('seller-api');
	}
}