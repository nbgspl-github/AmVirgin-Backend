<?php

namespace App\Http\Controllers\Web\Customer;

use App\Http\Controllers\Base\BaseController;

class HomeController extends BaseController{
	/**
	 * HomeController constructor.
	 */
	public function __construct(){

	}

	public function index(){
		return view('customer.home.index');
	}
}