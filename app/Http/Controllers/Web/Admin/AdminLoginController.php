<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminLoginController extends BaseController{
	/*
	|--------------------------------------------------------------------------
	| Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles authenticating users for the application and
	| redirecting them to your home screen. The controller uses a trait
	| to conveniently provide its functionality to your applications.
	|
	*/

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	protected $redirectTo = null;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware('guest');
		$this->redirectTo = route('admin.home');
	}

	protected function guard() {
		return Auth::guard('admin');
	}

	public function showLoginForm() {
		return view('admin.auth.login');
	}

	public function login(Request $request) {
		$request->validate([
			'email' => ['bail', 'required', 'email'],
			'password' => ['bail', 'required', 'min:4', 'max:100'],
		]);
		$success = Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], boolval($request->remember));
		if (!$success) {
			notify()->error('Login failed');
			return redirect(route('admin.login'))->withInput($request->all());
		}
		else {
			notify()->error('Login success');
			return redirect($this->redirectTo);
		}
	}

	public function logout(Request $request) {
		Auth::guard('admin')->logout();
		return redirect(route('admin.login'));
	}
}
