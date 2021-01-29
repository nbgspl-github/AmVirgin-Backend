<?php

namespace App\Http\Modules\Admin\Controllers\Web;

use App\Http\Modules\Admin\Requests\SubscriptionPlan\StatusRequest;
use App\Http\Modules\Admin\Requests\SubscriptionPlan\StoreRequest;
use App\Http\Modules\Admin\Requests\SubscriptionPlan\UpdateRequest;
use App\Models\SubscriptionPlan;

class SubscriptionPlanController extends WebController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index ()
	{
		return view('admin.subscription-plans.index')->with('plans',
			SubscriptionPlan::all()
		);
	}

	public function create ()
	{
		return view('admin.subscription-plans.create');
	}

	public function edit (SubscriptionPlan $plan)
	{
		return view('admin.subscription-plans.edit')->with('plan', $plan);
	}

	public function store (StoreRequest $request) : \Illuminate\Http\RedirectResponse
	{
		SubscriptionPlan::query()->create($request->validated());
		return redirect()->route('admin.subscription-plans.index')->with('success', 'Plan created successfully.');
	}

	public function update (UpdateRequest $request, SubscriptionPlan $plan) : \Illuminate\Http\RedirectResponse
	{
		$plan->update($request->validated());
		return redirect()->route('admin.subscription-plans.index')->with('success', 'Plan details updated successfully.');
	}

	public function status (StatusRequest $request, SubscriptionPlan $plan) : \Illuminate\Http\JsonResponse
	{
		$plan->update($request->validated());
		return responseApp()->prepare(
			[], \Illuminate\Http\Response::HTTP_OK, 'Status updated successfully.'
		);
	}

	/**
	 * @param SubscriptionPlan $plan
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Exception
	 */
	public function delete (SubscriptionPlan $plan) : \Illuminate\Http\JsonResponse
	{
		$plan->delete();
		return responseApp()->prepare(
			[], \Illuminate\Http\Response::HTTP_OK, 'Subscription plan deleted successfully.'
		);
	}
}