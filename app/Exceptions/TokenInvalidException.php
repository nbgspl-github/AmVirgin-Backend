<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class TokenInvalidException extends Exception{
	public function __construct($message = "The token you provided with the request is either invalid or is expired."){
		parent::__construct($message);
	}
}
