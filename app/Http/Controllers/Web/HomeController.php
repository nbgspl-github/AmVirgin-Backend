<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\WebController;
use Illuminate\Http\Request;

class HomeController extends WebController {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
//		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index() {
		return view('home');
	}
}