<?php

namespace App\Classes;

use Illuminate\Validation\Rules\Exists;

class Rule extends \Illuminate\Validation\Rule {

	/**
	 * Get a exists constraint builder instance for primary key of table.
	 * @param string $table
	 * @return Exists
	 */
	public static function existsPrimary(string $table) {
		return self::exists($table, 'id');
	}
}