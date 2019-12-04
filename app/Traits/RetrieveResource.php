<?php

namespace App\Traits;

trait RetrieveResource {

	/**
	 * Gets the data pointed to by this Id, or null if not found.
	 * @param null $id
	 * @return self
	 */
	public static function retrieve($id = null) {
		return self::find($id);
	}

}