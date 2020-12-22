<?php

namespace App\Exceptions;

use App\Models\Cart\Cart;
use Exception;

class CartAlreadySubmittedException extends Exception {
	public function __construct(Cart $cart) {
		parent::__construct(sprintf('Cart for specified session is already submitted. Please try again.'));
	}
}