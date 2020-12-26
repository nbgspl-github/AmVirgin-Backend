<?php

namespace App\Http\Modules\Admin\Controllers\Web\Users;

use App\Http\Modules\Admin\Repository\User\Customer\Contracts\CustomerRepository;
use App\Http\Requests\Admin\Customers\StoreRequest;
use App\Http\Requests\Admin\Customers\UpdateRequest;
use App\Models\Auth\Customer;

class CustomerController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	protected CustomerRepository $repository;

	public function __construct (CustomerRepository $repository)
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
		$this->repository = $repository;
	}

	public function index ()
	{

		dd($this->repository->recentPaginated($this->paginationChunk()));
		return view('admin.customers.index')->with(
			'users', $this->repository->recentPaginated($this->paginationChunk())
		);
	}

	public function create ()
	{
		return view('admin.customers.create');
	}

	public function edit (Customer $customer)
	{
		return view('admin.customers.edit')->with('customer', $customer);
	}

	public function store (StoreRequest $request) : \Illuminate\Http\RedirectResponse
	{
		$this->repository->create($request->validated());
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
		$this->repository->update($customer, $request->validated());
		return redirect()->route(
			'admin.customers.index'
		)->with('success', 'Customer details updated successfully.');
	}

	/**
	 * @param Customer $customer
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Exception
	 */
	public function destroy (Customer $customer) : \Illuminate\Http\JsonResponse
	{
		$this->repository->delete($customer);
		return response()->json(
			[]
		);
	}
}