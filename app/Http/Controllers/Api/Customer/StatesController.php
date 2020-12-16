<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\ApiController;
use App\Models\State;

class StatesController extends ApiController
{
	public function index ($countryId)
	{
		$states = State::where('countryId', $countryId)->get();
		$states->transform(function (State $state) {
			return [
				'id' => $state->getKey(),
				'name' => $state->name,
			];
		});
		return responseApp()->status(\Illuminate\Http\Response::HTTP_OK)->message(function () use ($states) {
			return sprintf('Found %d states for the given country.', $states->count());
		})->setValue('data', $states)->send();
	}

	protected function guard ()
	{
		return auth('seller-api');
	}
}