<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\BaseController;
use App\Interfaces\Roles;
use App\Models\Customer;
use App\Models\Genre;
use App\Rules\UniqueExceptSelf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomerController extends BaseController{
	public function index(){
		$users = Customer::all();
		return view('admin.customers.index')->with('users', $users);
	}

	public function create(Request $request){
		return view('admin.customers.create');
	}

	public function edit($id = null){
		$customer = Customer::retrieve($id);
		if ($customer != null) {
			return view('admin.customers.edit')->with('customer', $customer);
		}
		else {
			notify()->error(trans('admin.customers.not-found'));
			return redirect(route('admin.genres.index'));
		}
	}

	public function store(Request $request) {
		$validator = Validator::make($request->all(), [
			'name' => ['bail', 'required', 'string', 'min:4', 'max:50'],
			'mobile' => ['bail', 'required', 'digits:10', Rule::unique('customers', 'mobile')],
			'email' => ['bail', 'required', 'email', Rule::unique('customers', 'email')],
			'password' => ['bail', 'required', 'string', 'min:4', 'max:128'],
			'active' => ['bail', 'required', Rule::in([0, 1])],
		]);
		if ($validator->fails()) {
			return responseWeb()->
			route('admin.customers.create')->
			data($request->all())->
			error($validator->errors()->first())->
			send();
		}
		else {
			Customer::create($request->all());
			return responseWeb()->
			route('admin.customers.index')->
			success(trans('admin.customers.store-success'))->
			send();
		}
	}

	public function update(Request $request, $id = null) {
		$customer = Customer::retrieve($id);
		if ($customer == null) {
			return responseWeb()->
			error(trans('admin.customers.not-found'))->
			back()->
			send();
		}
		$validator = Validator::make($request->all(), [
			'name' => ['bail', 'required', 'string', 'min:4', 'max:50'],
			'mobile' => ['bail', 'required', 'digits:10', new UniqueExceptSelf(Customer::class, 'mobile', $request->mobile, $id, 'Mobile number')],
			'email' => ['bail', 'required', 'email', new UniqueExceptSelf(Customer::class, 'email', $request->email, $id, 'Email address')],
			'active' => ['bail', 'required', Rule::in([0, 1])],
		]);
		if ($validator->fails()) {
			return responseWeb()->
			route('admin.customers.edit', $id)->
			data($request->all())->
			error($validator->errors()->first())->
			send();
		}
		else {
			$customer->update($request->all());
			return responseWeb()->
			route('admin.customers.index')->
			success(trans('admin.customers.update-success'))->
			send();

		}
	}
}