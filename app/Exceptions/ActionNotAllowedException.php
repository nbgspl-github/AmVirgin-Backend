<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ActionNotAllowedException extends Exception
{
	public function __construct ($message = "The request action is not allowed for the user/resource.", $code = 0, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
