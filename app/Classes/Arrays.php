<?php

namespace App\Classes;

class Arrays extends \Illuminate\Support\Arr{
	const Empty = [];

	public static function fill($startIndex, $length, $value): array{
		return array_fill($startIndex, $length, $value);
	}

	public static function isArray($array): bool{
		return is_array($array);
	}

	public static function push(array &$array, ...$item): int{
		return array_push($array, $item);
	}

	public static function keyExists($key, array $array): bool{
		return array_key_exists($key, $array);
	}
}