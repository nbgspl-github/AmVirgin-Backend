<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\BaseController;

class HomeController extends BaseController{
	/**
	 * HomeController constructor.
	 */
	public function __construct(){

	}

	public function index(){
		return 'Seller home';
	}
}