<?php

namespace App\Http\Controllers\Web\Customer;

use App\Http\Controllers\Base\WebController;

class HomeController extends WebController {
	/**
	 * HomeController constructor.
	 */
	public function __construct() {

	}

	public function index() {
		return view('customer.home.index');
	}
}