<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class QueryProviderNotSpecifiedException extends Exception{
	public function __construct($message = ""){
		parent::__construct("No query provider was specified for model " . static::class);
	}
}
