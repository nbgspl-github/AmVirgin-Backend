<?php

namespace App\Http\Modules\Customer\Controllers\Api\Entertainment;

use App\Models\SubscriptionPlan;
use App\Resources\Subscriptions\Customer\SubscriptionResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class SubscriptionController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
    public function index (): JsonResponse
    {
        return responseApp()->prepare(
            SubscriptionResource::collection(SubscriptionPlan::query()->active(true)->get()), Response::HTTP_OK, 'Listing all available subscription plans.', 'data'
        );
    }
}