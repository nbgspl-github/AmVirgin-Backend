<?php

namespace App\Library\Utils\Extensions;

use Illuminate\Validation\Rules\Exists;

class Rule extends \Illuminate\Validation\Rule
{

	/**
	 * Get a exists constraint builder instance for primary key of table.
	 * @param string $table
	 * @param string $column
	 * @return Exists
	 */
	public static function existsPrimary(string $table, string $column = 'id') {
		return self::exists($table, $column);
	}

	public static function minimum($value) {
		return sprintf('min:%s', $value);
	}

	public static function maximum($value) {
		return sprintf('max:%s', $value);
	}

	public static function size($value) {
		return sprintf('size:%s', $value);
	}
}