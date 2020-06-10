<?php

namespace App\Traits;

/**
 * Trait FluentConstructor
 * @package App\Traits
 */
trait FluentConstructor {

	/**
	 * Makes a new instance of Eloquent model.
	 * @return self
	 */
	public static function newObject() {
		return new self();
	}
}