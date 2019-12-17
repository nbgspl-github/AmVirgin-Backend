<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Seller;
use App\Traits\ValidatesRequest;

class SellerController extends BaseController{
	use ValidatesRequest;

	protected $rules;

	public function __construct(){
		$this->rules = config('rules.admin.customers');
	}

	public function index(){
		$payload = Seller::retrieveAll();
		return view('admin.sellers.index')->with('sellers', $payload);
	}

	public function create(){

	}

	public function edit($id){

	}

	public function store(){

	}

	public function update($id){

	}

	public function delete($id){

	}
}