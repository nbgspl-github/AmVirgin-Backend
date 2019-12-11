<?php

namespace App\Http\Controllers\App\Seller;

use App\Exceptions\ResourceConflictException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\App\Auth\BaseAuthentication;
use App\Http\Resources\Auth\Seller\AuthProfileResource;
use App\Interfaces\StatusCodes;
use App\Models\Customer;
use App\Models\Seller;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Auth functionality for Seller.
 * @package App\Http\Controllers\App\Seller\Auth
 */
class AuthController extends BaseAuthentication{
	/**
	 * @var array
	 */
	protected $rules;

	/**
	 * AuthController constructor.
	 */
	public function __construct(){
		parent::__construct();
		$this->rules = config('rules.auth.seller');
	}

	protected function authTarget(){
		return Seller::class;
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
		return Auth::guard('seller-api');
	}

	protected function shouldAllowOnlyActiveUsers(){
		return true;
	}
}