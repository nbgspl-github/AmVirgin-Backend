<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ActionInvalidException extends Exception
{
	public function __construct ($message = "The requested status is invalid for this order.", $code = 0, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
