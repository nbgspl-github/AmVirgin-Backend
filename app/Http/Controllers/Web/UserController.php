<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Base\WebController;
use App\Models\User;

class UserController extends WebController {
	public function index($id = null) {
		if ($id == null) {
			$users = User::all();
			return view('users.list')->with('users', $users);
		} else {
			$user = User::find($id);
			if ($user == null) {
				return redirect(route('users.all'))->with('error', 'Could not find user by that Id.');
			} else {
				return view('users.single')->with('user', $user);
			}
		}
	}
}