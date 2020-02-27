<?php

namespace App\Http\Controllers\App\Customer;

use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\ShippingAddress;
use App\Resources\Addresses\Customer\ShippingAddressResource;

class ShippingAddressesController extends ExtendedResourceController {

	public function index() {
		$shippingAddresses = ShippingAddress::where('customerId', $this->guard()->id())->get();
		$shippingAddresses = ShippingAddressResource::collection($shippingAddresses);
		return responseApp()->status(HttpOkay)->message(function () use ($shippingAddresses) {
			return sprintf('Found %d addresses for this customer', $shippingAddresses->count());
		})->setValue('data', $shippingAddresses)->send();
	}

	public function store() {

	}

	protected function guard() {
		return auth('customer-api');
	}
}