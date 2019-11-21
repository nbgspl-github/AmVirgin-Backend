<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Base\AppController;
use App\Interfaces\Roles;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends AppController {

	public function index(int $id = null) {
		if ($id == null) {
			$users = User::where('role', Roles::User)->get();
			$index = 1;
			$users->transform(function (User $user) use (&$index) {
				return [
					'id' => $user->getId(),
					'name' => $user->getName(),
					'mobile' => $user->getMobile(),
					'email' => $user->getEmail(),
					'status' => __status($user->getStatus()),
					'index' => $index++,
				];
			});
			return;
		} else {
			$user = User::find($id);
			if ($user == null) {
				return [];
			} else {
				return [
					'id' => $user->getId(),
					'name' => $user->getName(),
					'mobile' => $user->getMobile(),
					'email' => $user->getEmail(),
					'status' => __status($user->getStatus()),
				];
			}
		}
	}

	public function store(Request $request) {

	}
}