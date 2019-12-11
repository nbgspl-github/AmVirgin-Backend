<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;

class DashboardController extends BaseController{

	/**
	 * DashboardController constructor.
	 */
	public function __construct(){

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
