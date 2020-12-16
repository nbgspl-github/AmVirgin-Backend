<?php

namespace App\Classes\Builders\Notifications;

use App\Traits\FluentConstructor;

class PushNotificationBuilder
{
	use FluentConstructor;

	/**
	 * Gets headers to be included in Request.
	 * @return array
	 */
	private function headers ()
	{
		return [
			'Authorization' => env('FIREBASE_API_KEY'),
			'Content-Type' => 'application/json',
			'Accept' => 'application/json',
		];
	}
}