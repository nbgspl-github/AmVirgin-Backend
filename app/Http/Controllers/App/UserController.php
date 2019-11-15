<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Base\AppController;
use App\Http\Resources\UserResource;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Auth;

class UserController extends AppController {
	public function index(int $id = null) {
		$user = null;
		if ($id == null) {
			$user = Auth::user();
		} else {
			$user = User::find($id);
		}
		return new UserResource($user);
	}
}