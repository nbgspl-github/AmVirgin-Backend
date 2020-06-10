<?php

namespace App\Traits;

use App\Classes\Builders\Notifications\PushNotification;

trait BroadcastPushNotifications {
	use InteractsWithHttp;

	public function pushNow(PushNotification $pushNotification) {
		return $this->requestPost(env('FIREBASE_URL'), $pushNotification);
	}

	public function pushLater(PushNotification $pushNotification) {

	}
}