<?php

namespace App\Exceptions;

use App\Models\Cart;
use Exception;
use Throwable;

class CartAlreadySubmittedException extends Exception {
	public function __construct(Cart $cart) {
		parent::__construct(sprintf('Cart for specified session is already submitted. Please try again.'));
	}
}
