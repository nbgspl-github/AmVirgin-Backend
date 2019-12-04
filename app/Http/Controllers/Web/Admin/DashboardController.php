<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Base\WebController;
use Illuminate\Contracts\Support\Renderable;

class DashboardController extends WebController {

	/**
	 * DashboardController constructor.
	 */
	public function __construct() {

	}

	/**
	 * Show the application dashboard.
	 *
	 * @return Renderable
	 */
	public function index() {
		return view('admin.home.dashboard');
	}
}
