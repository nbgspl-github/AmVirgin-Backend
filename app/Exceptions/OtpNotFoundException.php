<?php

namespace App\Exceptions;

use Exception;

class OtpNotFoundException extends Exception{
	public function __construct(){
		parent::__construct('No otp was generated for your mobile number. Please retry from begin.');
	}
}