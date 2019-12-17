<?php

namespace App\Http\Controllers\Web\Admin;

use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Interfaces\Tables;
use App\Models\Customer;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends BaseController{
	use ValidatesRequest;

	protected $rules;

	public function __construct(){
		$this->rules = config('rules.admin.customers');
	}

	public function index(){
		$users = Customer::retrieveAll();
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
			return responseWeb()->route('admin.customers.index')->error(trans('admin.customers.not-found'))->send();
		}
	}

	public function store(Request $request){
		$response = null;
		try {
			$payload = $this->requestValid($request, $this->rules['store']);
			Customer::create($payload);
			$response = responseWeb()->route('admin.customers.index')->success(__('strings.customer.store.success'));
		}
		catch (ValidationException $exception) {
			$response = responseWeb()->back()->data($request->all())->error($exception->getError());
		}
		catch (Exception $exception) {
			$response = responseWeb()->back()->data($request->all())->error($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function update(Request $request, $id = null){
		$response = null;
		$customer = Customer::retrieve($id);
		try {
			if ($customer == null)
				throw new ModelNotFoundException(__('strings.customer.not-found'));
			$additional = [
				'mobile' => [Rule::unique(Tables::Customers, 'mobile')->ignore($customer->getKey())],
				'email' => [Rule::unique(Tables::Customers, 'email')->ignore($customer->getKey())],
			];
			$payload = $this->requestValid($request, $this->rules['update'], $additional);
			$customer->update($payload);
			$response = responseWeb()->route('admin.customers.index')->success(__('strings.customer.update.success'));
		}
		catch (ModelNotFoundException $exception) {
			$response = responseWeb()->route('admin.customers.index')->error($exception->getMessage());
		}
		catch (ValidationException $exception) {
			$response = responseWeb()->back()->data($request->all())->error($exception->getError());
		}
		catch (Exception $exception) {
			$response = responseWeb()->route('admin.customers.index')->error($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}
}