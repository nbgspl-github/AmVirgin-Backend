<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Base\WebController;

class DashboardController extends WebController {

	/**
	 * DashboardController constructor.
	 */
	public function __construct() {

	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index() {
		return view('admin.home.dashboard');
	}

	public function seller() {
		return view('thirdParty.seller.home');
	}
}
