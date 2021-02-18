<?php

namespace App\Http\Modules\Customer\Controllers\Api\Entertainment;

use App\Models\SubscriptionPlan;
use App\Resources\Subscriptions\Customer\SubscriptionResource;

class SubscriptionController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function index ()
	{
		$subscriptions = SubscriptionPlan::where([
			['active', true],
		])->get();
		$subscriptions = SubscriptionResource::collection($subscriptions);
		return responseApp()->status(\Illuminate\Http\Response::HTTP_OK)->message('Listing all available subscriptions.')->setValue('data', $subscriptions)->send();
	}
}