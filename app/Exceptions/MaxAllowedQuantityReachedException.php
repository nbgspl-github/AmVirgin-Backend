<?php

namespace App\Exceptions;

use App\Classes\Cart\CartItem;
use Exception;

class MaxAllowedQuantityReachedException extends Exception {
	public function __construct(CartItem $cartItem) {
		parent::__construct(sprintf('Maximum allowed units for \'%s\' are %d for a single user in a single session.', $cartItem->getProduct()->getName(), $cartItem->getMaxAllowedQuantity()));
	}
}
