<?php

namespace App\Exceptions;

use App\Classes\Cart\CartItem;
use Exception;

class CartItemNotFoundException extends Exception{

	public function __construct(){
		parent::__construct('Item does not exist in this cart.');
	}
}