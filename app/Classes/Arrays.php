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

	public static function each(array $array, callable $callback){
		foreach ($array as $item) {
			call_user_func($callback, $item);
		}
	}

	public static function eachAssociative(array $array, callable $callback){
		foreach ($array as $key => $value) {
			call_user_func($callback, $key, $value);
		}
	}

	public static function containsValueIndexed(array $array, $value){
		foreach ($array as $item) {
			if ($item == $value) {
				return true;
			}
		}
		return false;
	}

	public static function reverse(array $array): array{
		return array_reverse($array);
	}
}