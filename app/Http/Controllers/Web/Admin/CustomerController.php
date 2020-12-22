<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Customers\StoreRequest;
use App\Http\Requests\Admin\Customers\UpdateRequest;
use App\Models\Auth\Customer;

class CustomerController extends BaseController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
	}

	public function index ()
	{
		$users = Customer::query()->paginate();
		return view('admin.customers.index')->with('users', $users);
	}

	public function create ()
	{
		return view('admin.customers.create');
	}

	public function edit (Customer $customer)
	{
		return view('admin.customers.show')->with('customer', $customer);
	}

	public function store (StoreRequest $request) : \Illuminate\Http\RedirectResponse
	{
		Customer::query()->create(
			$request->validated()
		);
		return redirect()->route(
			'admin.customers.index'
		);
	}

	public function show (Customer $customer) : \Illuminate\Http\JsonResponse
	{
		return response()->json(
			view('admin.customers.show')->with('customer', $customer)->render()
		);
	}

	public function update (UpdateRequest $request, Customer $customer) : \Illuminate\Http\RedirectResponse
	{
		$customer->update(
			$request->validated()
		);
		return redirect()->route(
			'admin.customers.index'
		);
	}

	/**
	 * @param Customer $customer
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Exception
	 */
	public function destroy (Customer $customer) : \Illuminate\Http\JsonResponse
	{
		$customer->delete();
		return response()->json(
			[]
		);
	}
}