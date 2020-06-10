<?php

namespace App\Classes\Builders\Notifications;

class PushNotification {
	/**
	 * @var string
	 */
	private $appLogo;

	/**
	 * @var array
	 */
	private $headers;

	/**
	 * @var string
	 */
	private $recipient;

	/**
	 * @var array
	 */
	private $recipients = [];

	public static function notificationBuilder() {
		return PushNotificationBuilder::instance();
	}

	public static function payloadBuilder() {
		return PushNotificationPayloadBuilder::instance();
	}

	/**
	 * Prepares a notification for dispatching.
	 * @return array
	 */
	public function prepare() {
		return [];
	}
}