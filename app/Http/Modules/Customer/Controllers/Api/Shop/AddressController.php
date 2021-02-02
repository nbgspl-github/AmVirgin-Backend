<?php

namespace App\Http\Modules\Customer\Controllers\Api\Shop;

use App\Exceptions\ValidationException;
use App\Library\Enums\Common\Tables;
use App\Models\Address\Address;
use App\Resources\Addresses\Customer\AddressResource;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Throwable;

class AddressController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
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

	public function index ()
	{
		$shippingAddresses = Address::where('customerId', $this->customer()->id)->get();
		$shippingAddresses = AddressResource::collection($shippingAddresses);
		return responseApp()->status(\Illuminate\Http\Response::HTTP_OK)->message(function () use ($shippingAddresses) {
			return sprintf('Found %d addresses for this customer', $shippingAddresses->count());
		})->setValue('data', $shippingAddresses)->send();
	}

	public function store ()
	{
		$response = responseApp();
		$validated = null;
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['store']);
			try {
				$address = Address::where([
					['type', $validated->type],
					['customerId', $this->customer()->id],
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
				$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Shipping address updated successfully.');
			} catch (ModelNotFoundException $exception) {
				Address::create([
					'customerId' => $this->customer()->id,
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
				$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Shipping address saved successfully.');
			}
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function update ($id)
	{
		$response = responseApp();
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['update']);
			$address = Address::where([
				['customerId', $this->customer()->id],
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
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Shipping address updated successfully.');
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find address for that key.');
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function delete ($id)
	{
		$response = responseApp();
		try {
			$address = Address::where([
				['customerId', $this->customer()->id],
				['id', $id],
			])->firstOrFail();
			$address->delete();
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Shipping address deleted successfully.');
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find address for that key.');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}