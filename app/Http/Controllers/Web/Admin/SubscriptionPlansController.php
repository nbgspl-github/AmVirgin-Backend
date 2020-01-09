<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\BaseController;
use App\Models\SubscriptionPlan;
use App\Traits\ValidatesRequest;
use Throwable;

class SubscriptionPlansController extends BaseController{
	use ValidatesRequest;

	protected $rules;

	public function __construct(){
		$this->rules = config('admin.subscription-plans');
	}

	public function index(){
		$payload = SubscriptionPlan::all();
		return view('admin.subscription-plans.index')->with('plans', $payload);
	}

	public function create(){
		return view('admin.subscription-plans.create');
	}

	public function show($id){

	}

	public function store(){
		$response = responseWeb();
		try {
			$payload = $this->requestValid(request(), $this->rules['store']);
			SubscriptionPlan::create($payload);
		}
		catch (Throwable $exception) {

		}
		finally {

		}
	}

	public function update($id){

	}

	public function updateStatus($id){

	}

	public function delete($id){

	}
}