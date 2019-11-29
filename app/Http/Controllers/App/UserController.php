<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Base\AppController;
use App\Interfaces\Roles;
use App\Models\Customer;
use Illuminate\Http\Request;

class UserController extends AppController {

	public function index(int $id = null) {
		if ($id == null) {
			$users = Customer::where('role', Roles::Customer)->get();
			$index = 1;
			$users->transform(function (Customer $user) use (&$index) {
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
			$user = Customer::find($id);
			if ($user == null) {
				return [];
			}
			else {
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