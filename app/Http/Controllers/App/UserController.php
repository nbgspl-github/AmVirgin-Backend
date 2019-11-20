<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Base\AppController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends AppController {
	public function index(int $id = null) {
		$users = User::all();
		$index = 1;
		$users->transform(function (User $user) use (&$index) {
			return [
				'id' => $user->getId(),
				'name' => $user->getName(),
				'mobile' => $user->getMobile(),
				'email' => $user->getEmail(),
				'index' => $index++,
			];
		});
		return $users;
	}
}