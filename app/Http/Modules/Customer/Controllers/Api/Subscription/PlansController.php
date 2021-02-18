<?php

namespace App\Http\Modules\Customer\Controllers\Api\Subscription;

class PlansController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_CUSTOMER)->except('index');
	}

	public function index () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			\App\Http\Modules\Customer\Resources\Subscription\SubscriptionResource::collection(
				\App\Models\SubscriptionPlan::query()->get()
			)
		);
	}
}