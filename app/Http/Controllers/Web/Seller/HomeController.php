<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Base\BaseController;

class HomeController extends BaseController{
	public function index(){
		return view('seller.home.dashboard');
	}
}