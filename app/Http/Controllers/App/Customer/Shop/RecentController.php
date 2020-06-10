<?php

namespace App\Http\Controllers\App\Customer\Shop;

use App\Http\Controllers\Web\ExtendedResourceController;

class RecentController extends ExtendedResourceController {
	public function __construct() {
		parent::__construct();
	}

	public function index() {

	}

	protected function guard() {
		return auth('customer-api');
	}
}