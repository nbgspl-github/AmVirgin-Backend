<?php

namespace App\Classes\Singletons;

use GuzzleHttp\Client;

/**
 * GuzzleHttp client.
 * @package App\Classes\Singletons
 */
class NetworkClient {
	private static $client;

	private function __construct() {

	}

	/**
	 * Returns the global instance of GuzzleHttp client.
	 * @return Client
	 */
	public static function get() {
		if (self::$client == null)
			self::$client = new Client();
		return self::$client;
	}
}