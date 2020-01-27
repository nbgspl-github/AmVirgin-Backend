<?php

namespace App\Classes\Builders\Notifications;

use App\Contracts\FluentConstructor;

class PushNotificationBuilder implements FluentConstructor {

	/**
	 * Gets headers to be included in Request.
	 * @return array
	 */
	private function headers() {
		return [
			'Authorization' => env('FIREBASE_API_KEY'),
			'Content-Type' => 'application/json',
			'Accept' => 'application/json',
		];
	}

	/**
	 *  Makes a new instance and returns it.
	 * @return self
	 */
	public static function instance() {
		return new self();
	}
}