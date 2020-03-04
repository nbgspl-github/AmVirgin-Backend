<?php

namespace App\Http\Controllers\App\Customer;

use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\SubscriptionPlan;
use App\Resources\Subscriptions\Customer\SubscriptionResource;

class SubscriptionController extends ExtendedResourceController {
	public function index() {
		$subscriptions = SubscriptionPlan::where([
			['active', true],
		])->get();
		$subscriptions = SubscriptionResource::collection($subscriptions);
		return responseApp()->status(HttpOkay)->message('Listing all available subscriptions.')->setValue('data', $subscriptions)->send();
	}

	protected function guard() {
		return auth('customer-api');
	}
}