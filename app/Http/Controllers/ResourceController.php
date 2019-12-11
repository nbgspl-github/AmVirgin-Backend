<?php

namespace App\Http\Controllers\Base;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class ResourceController extends BaseController{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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

	/**
	 * Provide a slug of class with which this controller will be bound.
	 * @return Model|Authenticatable
	 */
	protected abstract function provider();

	protected abstract function resource();

	protected abstract function collection();
}
