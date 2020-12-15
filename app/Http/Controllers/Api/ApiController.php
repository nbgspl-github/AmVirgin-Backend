<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Auth\Customer;
use App\Models\Auth\Seller;
use App\Traits\FluentResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class ApiController extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	use FluentResponse;

	protected const CUSTOMER_API = 'customer-api';
	protected const SELLER_API = 'seller-api';

	/**
	 * @return Seller|Customer
	 */
	protected function user ()
	{
		return $this->guard()->user();
	}

	protected function userId ()
	{
		return $this->guard()->user()->getKey();
	}

	protected function evaluate (callable $closure, ...$arguments)
	{
		return call_user_func($closure, $arguments);
	}

	protected abstract function guard ();
}