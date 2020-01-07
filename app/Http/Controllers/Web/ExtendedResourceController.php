<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\BaseController;
use App\Traits\FluentResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class ExtendedResourceController extends BaseController{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	use FluentResponse;

	protected function user(){
		return $this->guard()->user();
	}

	protected function evaluate(callable $closure, ...$arguments){
		return call_user_func($closure, $arguments);
	}

	protected abstract function guard();
}