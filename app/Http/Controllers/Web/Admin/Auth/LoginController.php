<?php

namespace App\Http\Controllers\Web\Admin\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends BaseController
{
	use AuthenticatesUsers;

	protected $redirectTo = null;

	public function __construct ()
	{
		parent::__construct();
		$this->middleware('guest');
		$this->redirectTo = route('admin.home');
	}

	public function showLoginForm ()
	{
		if ($this->guard()->user() == null) {
			return view('admin.auth.login');
		} else
			return redirect(route('admin.home'));
	}

	public function login (Request $request)
	{
		$request->validate([
			'email' => ['bail', 'required', 'email'],
			'password' => ['bail', 'required', 'min:4', 'max:100'],
		]);
		$success = Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], boolval($request->remember));
		if (!$success) {
			return responseWeb()->data(\request()->except('password'))->error('Your login credentials are invalid!')->send();
		} else {
			notify()->success('Welcome ' . \auth('admin')->user()->name() . '!');
			return redirect()->intended('/admin');
		}
	}

	public function logout (Request $request)
	{
		$name = \auth('admin')->user()->name();
		$this->guard()->logout();
		$request->session()->invalidate();
		notify()->success('Goodbye ' . $name . '!');
		return redirect(route('admin.login'));
	}

	protected function guard ()
	{
		return Auth::guard('admin');
	}
}
