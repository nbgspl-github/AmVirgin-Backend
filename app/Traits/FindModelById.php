<?php

namespace App\Traits;

trait FindModelById {

	/**
	 * Gets the data pointed to by this Id, or null if not found.
	 * @param null $id
	 * @return parent
	 */
	public static function retrieve($id = null) {
		return parent::find($id);
	}

}