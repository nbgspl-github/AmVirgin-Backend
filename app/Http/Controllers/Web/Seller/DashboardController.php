<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Base\WebController;

class DashboardController extends WebController {
	public function index() {
		return view('seller.home.dashboard');
	}
}