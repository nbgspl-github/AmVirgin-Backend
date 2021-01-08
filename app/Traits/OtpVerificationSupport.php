<?php

namespace App\Traits;

trait OtpVerificationSupport
{
	public function generateOtp ($length) : int
	{
		switch ($length) {
			case 5:
				return mt_rand(11111, 99999);

			case 6:
				return mt_rand(111111, 999999);

			case 7:
				return mt_rand(1111111, 9999999);

			case 8:
				return mt_rand(11111111, 99999999);

			default:
				return mt_rand(1111, 9999);
		}
	}

	/**
	 * @return int
	 */
	public function sendVerificationOtp () : int
	{
		$otp = $this->generateOtp(4);
		$client = new \GuzzleHttp\Client();
		try {
			$response = $client->post('https://www.bulksmsplans.com/api/send_sms', [
				'form_params' => [
					'api_id' => env("SMS_API_ID"),
					'api_password' => env("SMS_API_PASSWORD"),
					'sms_type' => 'OTP',
					'number' => $this->mobile,
					'message' => "Your one time password for authentication is {$otp}.",
					'sms_encoding' => 1,
					'sender' => env("SMS_API_SENDER"),
				]
			]);
			return $otp;
		} catch (\Throwable $e) {
			return $otp;
		}
	}

	/**
	 * @return mixed
	 */
	public function getOtp ()
	{
		return $this->otp;
	}

	public function setOtp ($otp)
	{
		$this->otp = $otp;
		return $this;
	}
}