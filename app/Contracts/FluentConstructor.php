<?php

namespace App\Contracts;

interface FluentConstructor {
	/**
	 *  Makes a new instance and returns it.
	 * @return self
	 */
	public static function instance();
}