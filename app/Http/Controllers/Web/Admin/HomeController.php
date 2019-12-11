<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Base\BaseController;

class HomeController extends BaseController{
	/**
	 * HomeController constructor.
	 */
	public function __construct(){

	}

	public function index(){
		return view('admin.home.dashboard');
	}
}