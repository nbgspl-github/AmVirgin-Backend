<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Video;

class HomeController extends BaseController{
	/**
	 * HomeController constructor.
	 */
	public function __construct(){
		parent::__construct();
		$this->middleware('auth:admin');
	}

	public function index(){
		$videoCount = Video::all()->count();
		$payload = [
			'video' => $videoCount,
		];
		return view('admin.home.dashboard')->with('payload', $payload);
	}
}