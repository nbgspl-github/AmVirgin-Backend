<?php

namespace App\Http\Controllers\App\Customer;

use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\State;

class StatesController extends ExtendedResourceController {
	public function index($countryId) {
		$states = State::where('countryId', $countryId)->get();
		$states->transform(function (State $state) {
			return [
				'id' => $state->getKey(),
				'name' => $state->name,
			];
		});
		return responseApp()->status(HttpOkay)->message(function () use ($states) {
			return sprintf('Found %d states for the given country.', $states->count());
		})->setValue('data', $states)->send();
	}

	protected function guard() {
		return auth('seller-api');
	}
}