<?php

namespace App\Traits;

trait NotifiableViaSms
{
	public function sendTextMessage (string $message) : bool
	{
		$client = new \GuzzleHttp\Client();
		try {
			$response = $client->post('https://www.bulksmsplans.com/api/send_sms', [
				'form_params' => [
					'api_id' => env("SMS_API_ID"),
					'api_password' => env("SMS_API_PASSWORD"),
					'sms_type' => 'Transactional',
					'number' => $this->mobile,
					'message' => $message,
					'sms_encoding' => 1,
					'sender' => env("SMS_API_SENDER"),
				]
			]);
			return true;
		} catch (\Throwable $e) {
			return false;
		}
	}

	/**
	 * @return int
	 */
	public function sendOtpMessage () : int
	{
		$otp = mt_rand(1111, 9999);
		$client = new \GuzzleHttp\Client();
		try {
			$response = $client->post('https://www.bulksmsplans.com/api/send_sms', [
				'form_params' => [
					'api_id' => env("SMS_API_ID"),
					'api_password' => env("SMS_API_PASSWORD"),
					'sms_type' => 'Transactional',
					'number' => $this->mobile,
					'message' => __('auth.otp.message', ['otp' => $otp]),
					'sms_encoding' => 1,
					'sender' => env("SMS_API_SENDER"),
				]
			]);
			return $otp;
		} catch (\Throwable $e) {
			return -1;
		}
	}

	/**
	 * @param string $mobile
	 * @return int
	 */
	public static function sendGuestOtpMessage (string $mobile) : int
	{
		$otp = mt_rand(1111, 9999);
		$client = new \GuzzleHttp\Client();
		try {
			$response = $client->post('https://www.bulksmsplans.com/api/send_sms', [
				'form_params' => [
					'api_id' => env("SMS_API_ID"),
					'api_password' => env("SMS_API_PASSWORD"),
					'sms_type' => 'Transactional',
					'number' => $mobile,
					'message' => __('auth.otp.message', ['otp' => $otp]),
					'sms_encoding' => 1,
					'sender' => env("SMS_API_SENDER"),
				]
			]);
			return $otp;
		} catch (\Throwable $e) {
			return -1;
		}
	}
}