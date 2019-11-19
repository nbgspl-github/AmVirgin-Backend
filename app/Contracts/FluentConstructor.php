<?php

namespace App\Contracts;

interface FluentConstructor {
	/**
	 *  Makes a new instance and returns it.
	 * @return mixed
	 */
	public static function makeNew();
}