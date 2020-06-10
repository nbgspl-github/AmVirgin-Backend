<?php

namespace App\Traits;

use App\Classes\Singletons\NetworkClient;
use Psr\Http\Message\ResponseInterface;

trait InteractsWithHttp {

	/**
	 * Sends an HTTP GET request and returns response.
	 * @param string $url
	 * @param array $options
	 * @return ResponseInterface
	 */
	private function requestGet(string $url, array $options = []) {
		$client = NetworkClient::get();
		return $client->get($url, $options);
	}

	/**
	 * Sends an HTTP POST request and returns response.
	 * @param string $url
	 * @param array $options
	 * @return ResponseInterface
	 */
	private function requestPost(string $url, array $options = []) {
		$client = NetworkClient::get();
		return $client->post($url, $options);
	}

	/**
	 * Sends an HTTP PUT request and returns response.
	 * @param string $url
	 * @param array $options
	 * @return ResponseInterface
	 */
	private function requestPut(string $url, array $options = []) {
		$client = NetworkClient::get();
		return $client->put($url, $options);
	}
}