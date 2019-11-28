<?php

namespace App\Http\Controllers\Web;

use App\Interfaces\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationsController {
	public function create() {
		return view('admin.notifications.create');
	}

	public function send(Request $request) {
		$validator = Validator::make($request->all(), [
			'title' => ['bail', 'required', 'string', 'min:1', 'max:75'],
			'content' => ['bail', 'required', 'string', 'min:1', 'max:1024'],
			'url' => ['bail', 'nullable', 'url'],
		]);
		if ($validator->fails()) {
			return response()->json([
				'message' => $validator->errors()->first(),
			], 400);
		} else {
			$customers = User::where('role', Roles::Customer)->chunk(100, function (User $chunk) {
				$chunk->each(function (User $customer) {

				});
			});
			return response()->json([
				'message' => 'Notification queued successfully.',
			]);
		}
	}
}