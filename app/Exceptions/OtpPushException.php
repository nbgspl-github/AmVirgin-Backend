<?php

namespace App\Exceptions;

use Exception;

class OtpPushException extends Exception{

	/**
	 * OtpPushException constructor.
	 */
	public function __construct(){
		parent::__construct('Failed to send a one time password. Please try again.');
	}
}
