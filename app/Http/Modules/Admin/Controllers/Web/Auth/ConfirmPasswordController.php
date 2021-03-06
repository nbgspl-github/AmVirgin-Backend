<?php

namespace App\Http\Modules\Admin\Controllers\Web\Auth;

use App\Http\Modules\Shared\Controllers\BaseController;
use Illuminate\Foundation\Auth\ConfirmsPasswords;

class ConfirmPasswordController extends BaseController
{
	/*
	|--------------------------------------------------------------------------
	| Confirm Password Controller
	|--------------------------------------------------------------------------
	|
	| This controller is responsible for handling password confirmations and
	| uses a simple trait to include the behavior. You're free to explore
	| this trait and override any functions that require customization.
	|
	*/

	use ConfirmsPasswords;

	/**
	 * Where to redirect users when the intended url fails.
	 *
	 * @var string
	 */
	protected $redirectTo = '/home';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct ()
	{
		parent::__construct();
		$this->middleware('auth');
	}
}