<?php

namespace App\Http\Controllers\App\Customer;

use App\Exceptions\ResourceConflictException;
use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\App\Auth\BaseAuthController;
use App\Http\Resources\Auth\Seller\AuthProfileResource;
use App\Interfaces\StatusCodes;
use App\Models\Customer;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Auth functionality for Customer.
 * @package App\Http\Controllers\App\Customer\Auth
 */
class AuthController extends BaseAuthController{
	/**
	 * @var array
	 */
	protected $rules;

	/**
	 * AuthController constructor.
	 */
	public function __construct(){
		parent::__construct();
		$this->rules = config('rules.auth.customer');
	}

	protected function authTarget(){
		return Customer::class;
	}

	protected function rulesExists(){
		return $this->rules['exists'];
	}

	protected function rulesLogin(){
		return $this->rules['login'];
	}

	protected function rulesRegister(){
		return $this->rules['register'];
	}

	protected function guard(){
		return Auth::guard('customer-api');
	}
}