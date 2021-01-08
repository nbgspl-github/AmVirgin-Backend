<?php

namespace App\Traits;

use App\Models\Settings;

trait GuestOtpVerificationSupport
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
	public function sendGuestOtp (string $mobile) : int
	{
		$length = Settings::getInt('otpLength', 4);
		$otp = $this->generateOtp($length);
		return $otp;
	}
}