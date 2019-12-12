<?php

namespace App\Http\Controllers\Web\Customer;

use App\Http\Controllers\BaseController;

class HomeController extends BaseController{
	/**
	 * HomeController constructor.
	 */
	public function __construct(){

	}

	public function index(){
		return 'Customer Home';
	}
}