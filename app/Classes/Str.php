<?php

namespace App\Classes;

class Str extends \Illuminate\Support\Str {
	const Empty = '';

	const NewLine = "\n";

	public static function equals(string $first = null, string $second = null) {
		return strcmp($first, $second) == 0;
	}
}