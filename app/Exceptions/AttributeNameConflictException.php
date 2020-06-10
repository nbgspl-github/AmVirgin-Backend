<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class AttributeNameConflictException extends Exception{
	public function __construct($message = 'An attribute with the same label and code already exists. Please try a different label.'){
		parent::__construct($message);
	}
}
