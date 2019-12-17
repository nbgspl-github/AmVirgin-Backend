<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\BaseController;
use App\Traits\ValidatesRequest;

class WebSeriesController extends BaseController{
	use ValidatesRequest;

	protected $rules;

	public function __construct(){
		$this->rules = config('rules.admin.series');
	}

	public function index(){

	}
}