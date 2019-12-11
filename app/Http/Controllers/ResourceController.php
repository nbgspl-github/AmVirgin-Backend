<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Attributes\AttributeCollection;
use App\Http\Resources\Attributes\AttributeResource;
use App\Traits\FluentResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class ResourceController extends BaseController{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	use FluentResponse;

	protected function index(){
		return $this->failed()->status(403)->send();
//		return $this->resource()::make(::paginate());
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

	/**
	 * @return AttributeResource
	 */
	protected abstract function resource();

	/**
	 * @return AttributeCollection
	 */
	protected abstract function collection();
}
