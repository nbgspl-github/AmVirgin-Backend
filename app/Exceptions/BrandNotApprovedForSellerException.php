<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class BrandNotApprovedForSellerException extends Exception{
	public function __construct($message = "You are not approved to sell under this brand. Please contact your administrator."){
		parent::__construct($message);
	}
}
