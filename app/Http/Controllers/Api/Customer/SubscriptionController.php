<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\ApiController;
use App\Models\SubscriptionPlan;
use App\Resources\Subscriptions\Customer\SubscriptionResource;

class SubscriptionController extends ApiController
{
	public function index ()
	{
		$subscriptions = SubscriptionPlan::where([
			['active', true],
		])->get();
		$subscriptions = SubscriptionResource::collection($subscriptions);
		return responseApp()->status(\Illuminate\Http\Response::HTTP_OK)->message('Listing all available subscriptions.')->setValue('data', $subscriptions)->send();
	}

	protected function guard ()
	{
		return auth('customer-api');
	}
}