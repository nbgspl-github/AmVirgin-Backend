<?php

namespace App\Exceptions;

use App\Classes\Cart\CartItem;
use Exception;

class CartItemNotFoundException extends Exception {

	public function __construct(CartItem $cartItem) {
		parent::__construct(sprintf('Item \'%s\' does not exist in this cart.', $cartItem->getProduct()->getName()));
	}
}