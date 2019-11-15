<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Base\WebController;

class DashboardController extends WebController {
	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index() {
		return view('dashboard');
	}
}