<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class InvalidCategoryException extends Exception{
	public function __construct($message = ""){
		parent::__construct('Product addition is not allowed for this category Please select a descendant.');
	}
}
