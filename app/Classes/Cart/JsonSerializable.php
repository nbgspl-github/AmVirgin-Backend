<?php

namespace App\Classes\Cart;

interface JsonSerializable extends \JsonSerializable {
	public static function jsonDeserialize(string $json, $parameter = null);
}