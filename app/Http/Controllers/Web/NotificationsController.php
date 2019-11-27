<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

class NotificationsController {
	public function create() {
		return view('notifications.create');
	}

	public function send(Request $request) {

	}
}