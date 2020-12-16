<?php

namespace App\Classes\Builders\Notifications;

use App\Traits\FluentConstructor;
use App\Traits\RedactArrayItems;

class PushNotificationPayloadBuilder
{
	use RedactArrayItems;
	use FluentConstructor;

	/**
	 * Predefined Firebase recognized keys.
	 */
	const Predefined = [
		'Title' => 'title',
		'Body' => 'body',
		'Notification' => 'notification',
	];

	/**
	 * Payload array.
	 * @var array
	 */
	private $payloadItems;

	/**
	 * PushNotificationPayloadBuilder constructor.
	 */
	private function __construct ()
	{
		$this->payloadItems = [];
	}

	/**
	 * Adds a new key/value pair to payload.
	 * @param string $key
	 * @param string $value
	 * @return $this
	 */
	public function addData (string $key, string $value)
	{
		$this->payloadItems[$key] = $value;
		return $this;
	}

	/**
	 * Gets an array containing all exposed attributes of this class,
	 * excluding the ones mentioned to redact.
	 * @param array $redact
	 * @return array
	 */
	public function serializeArray ($redact = [])
	{
		$payload = $this->redact($this->payloadItems, $redact);
		return $payload;
	}

	/**
	 * Gets a JsonObject containing all exposed attributes of this class,
	 * excluding the ones mentioned to redact.
	 * @param array $redact
	 * @return array
	 */
	public function serializeJson ($redact = [])
	{
		$payload = $this->redact($this->payloadItems, $redact);
		return jsonEncode($payload);
	}
}