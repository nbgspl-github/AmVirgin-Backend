<?php

namespace App\Http\Controllers\App\Customer;

use App\Http\Controllers\Web\ExtendedResourceController;

class SortsController extends ExtendedResourceController{
	public function index(){

	}

	protected function guard(){
		return auth('customer-api');
	}
}