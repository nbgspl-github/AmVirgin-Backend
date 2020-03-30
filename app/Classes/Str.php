<?php

namespace App\Classes;

class Str extends \Illuminate\Support\Str{
	const Empty = '';
	const Null = null;
	const NewLine = "\r\n";
	const WhiteSpace = ' ';

	public static function equals(string $first = null, string $second = null){
		return strcmp($first, $second) == 0;
	}

	public static function split($delimiter, $string): array{
		return explode($delimiter, $string);
	}

	public static function join($separator, array $pieces): string{
		return implode($separator, $pieces);
	}

	public static function trimExtraWhiteSpaces($string){
		return preg_replace('/\s+/', ' ', $string);
	}
}