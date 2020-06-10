<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait RetrieveCollection {

	/**
	 * Gets all the data within this resource type.
	 * @return Collection
	 */
	public static function retrieveAll() {
		return self::all();
	}

}