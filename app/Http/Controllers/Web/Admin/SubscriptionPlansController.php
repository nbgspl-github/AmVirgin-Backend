<?php

namespace App\Http\Controllers\Web\Admin;

use App\Classes\WebResponse;
use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Library\Enums\Common\Directories;
use App\Library\Enums\Common\Tables;
use App\Library\Utils\Uploads;
use App\Models\SubscriptionPlan;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;

class SubscriptionPlansController extends BaseController
{
	use ValidatesRequest;
	use FluentResponse;

	public function __construct ()
	{
		parent::__construct();
		$this->ruleSet->load('rules.admin.subscription-plans');
	}

	public function index ()
	{
		$payload = SubscriptionPlan::all();
		return view('admin.subscription-plans.index')->with('plans', $payload);
	}

	public function create ()
	{
		return view('admin.subscription-plans.create');
	}

	public function edit ($id)
	{
		$response = responseWeb();
		try {
			$plan = SubscriptionPlan::retrieveThrows($id);
			$response = view('admin.subscription-plans.edit')->with('plan', $plan);
		} catch (ModelNotFoundException $exception) {
			$response->route('admin.subscription-plans.index')->error('Could not find a subscription plan for that key.');
		} catch (Throwable $exception) {
			$response->back()->data(request()->all())->error($exception->getMessage());
		} finally {
			if ($response instanceof WebResponse)
				return $response->send();
			else
				return $response;
		}
	}

	public function show ($id)
	{

	}

	public function store ()
	{
		$response = responseWeb();
		try {
			$payload = $this->requestValid(request(), $this->rules('store'));
			$payload['slug'] = Str::slug($payload['name']);
			$payload['banner'] = Uploads::access()->putFile(Directories::SubscriptionPlans, request()->file('banner'));
			SubscriptionPlan::create($payload);
			$response->route('admin.subscription-plans.index')->success('Subscription plan created successfully.');
		} catch (ValidationException $exception) {
			$response->back()->data(request()->all())->error($exception->getError());
		} catch (Throwable $exception) {
			$response->back()->data(request()->all())->error($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function update ($id)
	{
		$response = responseWeb();
		try {
			$plan = SubscriptionPlan::retrieveThrows($id);
			$additional = [
				'name' => [Rule::unique(Tables::SubscriptionPlans, 'name')->ignore($id)],
			];
			$payload = $this->requestValid(request(), $this->rules('update'), $additional);
			$payload['slug'] = Str::slug($payload['name']);
			$payload = collect($payload)->filter()->toArray();
			if (request()->hasFile('banner')) {
				Uploads::deleteIfExists($plan->getBanner());
				$payload['banner'] = Uploads::access()->putFile(Directories::SubscriptionPlans, request()->file('banner'));
			}
			$plan->update($payload);
			$response->route('admin.subscription-plans.index')->success('Plan details updated successfully.');
		} catch (ModelNotFoundException $exception) {
			$response->route('admin.subscription-plans.index')->error('Could not find a subscription plan for that key.');
		} catch (ValidationException $exception) {
			$response->back()->data(request()->all())->error($exception->getError());
		} catch (Throwable $exception) {
			$response->back()->data(request()->all())->error($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function updateStatus ($id)
	{

	}

	public function delete ($id)
	{
		$response = $this->responseApp();
		try {
			$plan = SubscriptionPlan::retrieveThrows($id);
			$plan->delete();
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Successfully deleted subscription plan.');
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find subscription plan for that key.');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}