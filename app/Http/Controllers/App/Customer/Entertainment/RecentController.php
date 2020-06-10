<?php

namespace App\Http\Controllers\App\Customer\Entertainment;

use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\CustomerRecent;

class RecentController extends ExtendedResourceController {
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$recent = CustomerRecent::where([
			['customerId', $this->guard()->id()],
		])->get();

	}

	protected function guard() {
		return auth('customer-api');
	}
}