<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\BaseController;

class ReactController extends BaseController{
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		return view('seller.index');
	}
}