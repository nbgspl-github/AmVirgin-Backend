<?php

namespace App\Exceptions;

use Exception;

class OtpIncorrectException extends Exception{
	public function __construct(){
		parent::__construct('Your given one time password does not match the one we generated for your number.');
	}
}