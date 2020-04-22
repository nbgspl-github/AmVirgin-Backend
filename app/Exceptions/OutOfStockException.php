<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class OutOfStockException extends Exception{
	public function __construct($message = "The item you are trying to add is currently out of stock."){
		parent::__construct($message);
	}
}
