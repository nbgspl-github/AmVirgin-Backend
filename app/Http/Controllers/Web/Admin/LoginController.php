<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends BaseController{
	use AuthenticatesUsers;

	protected $redirectTo = null;

	public function __construct(){
		$this->middleware('guest');
		$this->redirectTo = route('admin.home');
	}

	public function showLoginForm(){
		return view('admin.auth.login');
	}

	public function login(Request $request){
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
			notify()->success('Logged in successfully');
			return redirect($this->redirectTo);
		}
	}

	public function logout(Request $request){
		Auth::guard('admin')->logout();
		return redirect(route('admin.login'));
	}

	protected function guard(){
		return Auth::guard('admin');
	}
}
