<?php

namespace App\Http\Controllers\App\Customer;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Interfaces\Tables;
use App\Models\ShippingAddress;
use App\Resources\Addresses\Customer\ShippingAddressResource;
use App\Traits\ExtendedRequestValidator;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Throwable;

class ShippingAddressesController extends ExtendedResourceController {
	use ValidatesRequest;
	protected array $rules;

	public function __construct() {
		parent::__construct();
		$this->rules = [
			'store' => [
				'name' => ['bail', 'required', 'string', 'min:1', 'max:255'],
				'mobile' => ['bail', 'required', 'digits:10'],
				'alternateMobile' => ['bail', 'nullable', 'digits:10'],
				'pinCode' => ['bail', 'required', 'digits_between:4,10'],
				'stateId' => ['bail', 'required', Rule::exists(Tables::States, 'id')],
				'address' => ['bail', 'required', 'string', 'min:4', 'max:255'],
				'locality' => ['bail', 'required', 'string', 'min:1', 'max:255'],
				'cityId' => ['bail', 'required', Rule::exists(Tables::Cities, 'id')],
				'type' => ['bail', 'required', 'string', Rule::in(['home', 'office', 'other'])],
				'saturdayWorking' => ['bail', 'required', 'boolean'],
				'sundayWorking' => ['bail', 'required', 'boolean'],
			],
			'update' => [
				'name' => ['bail', 'required', 'string', 'min:1', 'max:255'],
				'mobile' => ['bail', 'required', 'digits:10'],
				'alternateMobile' => ['bail', 'nullable', 'digits:10'],
				'pinCode' => ['bail', 'required', 'digits_between:4,10'],
				'stateId' => ['bail', 'required', Rule::exists(Tables::States, 'id')],
				'address' => ['bail', 'required', 'string', 'min:4', 'max:255'],
				'locality' => ['bail', 'required', 'string', 'min:1', 'max:255'],
				'cityId' => ['bail', 'required', Rule::exists(Tables::Cities, 'id')],
				'type' => ['bail', 'required', 'string', Rule::in(['home', 'office', 'other'])],
				'saturdayWorking' => ['bail', 'required', 'boolean'],
				'sundayWorking' => ['bail', 'required', 'boolean'],
			],
		];
	}

	public function index() {
		$shippingAddresses = ShippingAddress::where('customerId', $this->guard()->id())->get();
		$shippingAddresses = ShippingAddressResource::collection($shippingAddresses);
		return responseApp()->status(HttpOkay)->message(function () use ($shippingAddresses) {
			return sprintf('Found %d addresses for this customer', $shippingAddresses->count());
		})->setValue('data', $shippingAddresses)->send();
	}

	public function store() {
		$response = responseApp();
		$validated = null;
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['store']);
			try {
				$address = ShippingAddress::where([
					['type', $validated->type],
					['customerId', $this->guard()->id()],
				])->firstOrFail();
				$address->update([
					'name' => $validated->name,
					'mobile' => $validated->mobile,
					'alternateMobile' => $validated->alternateMobile,
					'pinCode' => $validated->pinCode,
					'stateId' => $validated->stateId,
					'address' => $validated->address,
					'locality' => $validated->locality,
					'cityId' => $validated->cityId,
					'saturdayWorking' => $validated->saturdayWorking,
					'sundayWorking' => $validated->sundayWorking,
				]);
				$response->status(HttpOkay)->message('Shipping address updated successfully.');
			}
			catch (ModelNotFoundException $exception) {
				ShippingAddress::create([
					'customerId' => $this->guard()->id(),
					'name' => $validated->name,
					'mobile' => $validated->mobile,
					'alternateMobile' => $validated->alternateMobile,
					'pinCode' => $validated->pinCode,
					'stateId' => $validated->stateId,
					'address' => $validated->address,
					'locality' => $validated->locality,
					'cityId' => $validated->cityId,
					'type' => $validated->type,
					'saturdayWorking' => $validated->saturdayWorking,
					'sundayWorking' => $validated->sundayWorking,
				]);
				$response->status(HttpOkay)->message('Shipping address saved successfully.');
			}
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function update($id) {
		$response = responseApp();
		try { 
			$validated = (object)$this->requestValid(request(), $this->rules['update']); 
			$address = ShippingAddress::where([
				['customerId', $this->guard()->id()],
				['id', $id],
			])->firstOrFail();
			$address->update([
				'name' => $validated->name,
				'mobile' => $validated->mobile,
				'alternateMobile' => $validated->alternateMobile,
				'pinCode' => $validated->pinCode,
				'stateId' => $validated->stateId,
				'address' => $validated->address,
				'locality' => $validated->locality,
				'cityId' => $validated->cityId,
				'saturdayWorking' => $validated->saturdayWorking,
				'sundayWorking' => $validated->sundayWorking,
			]);
			$response->status(HttpOkay)->message('Shipping address updated successfully.');
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find address for that key.');
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function delete($id) {
		$response = responseApp();
		try {
			$address = ShippingAddress::where([
				['customerId', $this->guard()->id()],
				['id', $id],
			])->firstOrFail();
			$address->delete();
			$response->status(HttpOkay)->message('Shipping address deleted successfully.');
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find address for that key.');
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	protected function guard() {
		return auth('customer-api');
	}
}