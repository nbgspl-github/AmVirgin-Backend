<?php

namespace App\Exceptions;

use App\Models\CartItem;
use Exception;

class MaxAllowedQuantityReachedException extends Exception {
	public function __construct(CartItem $cartItem) {
		parent::__construct(sprintf('Maximum allowed units for \'%s\' are %d for a single user in a single session.', $cartItem->product->getName(), CartItem::MaxAllowedQuantity));
	}
}
