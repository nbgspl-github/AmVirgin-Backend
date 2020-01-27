<?php

namespace App\Http\Controllers\Base;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

abstract class AppController extends BaseController{
	protected function index(){
		return $this->resource()::with($this->provider()::paginate());
	}

	protected function edit($id){

	}

	protected function store(){

	}

	protected function update(){

	}

	protected function delete(){

	}

	protected abstract function provider();

	protected abstract function resource();

	protected abstract function collection();
}
