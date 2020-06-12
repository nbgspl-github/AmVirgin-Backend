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
		return array_push($array, count($item) < 2 ? $item[0] : $item);
	}

	public static function keyExists($key, array $array): bool{
		return array_key_exists($key, $array);
	}

	public static function each(array $array, callable $callback){
		foreach ($array as $item) {
			call_user_func($callback, $item);
		}
	}

	public static function eachAssociative (array $array, callable $callback) {
		foreach ($array as $key => $value) {
			call_user_func($callback, $key, $value);
		}
	}

	/**
	 * Checks whether an array contains an item.
	 *
	 * @param array $haystack The array to search into
	 * @param $needle mixed Value to search for
	 * @param bool $associative True if the haystack is an associative array
	 * @return bool true if item is found, false otherwise
	 */
	public static function contains (array $haystack, $needle, bool $associative = false) : bool {
		if (!$associative)
			foreach ($haystack as $value) {
				if ($value == $needle)
					return true;
			}
		else
			foreach ($haystack as $key => $value) {
				if ($value == $needle)
					return true;
			}
		return false;
	}

	public static function containsValueIndexed (array $array, $value) {
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

	public static function length(array $array): int{
		return count($array);
	}

	public static function replaceValues(array &$destination, array $source){
		foreach ($source as $key => $value) {
			$destination[$key] = $value;
		}
	}

	public static function search($value, array $array, $default = null){
		$key = array_search($value, $array);
		return $key == false ? $default : $key;
	}

	public static function values(array $array): array{
		return array_values($array);
	}

	public static function countBetween(array $array, int $min, int $max): int{
		$count = 0;
		foreach ($array as $value) {
			if ($value >= $min && $value < $max)
				$count++;
		}
		return $count;
	}

	public static function loopFor($final, callable $callback, bool $inclusive = false, $initial = 0){
		for ($i = $initial; $inclusive ? $i <= $final : $i < $final; $final >= $initial ? $i++ : $i--) {
			call_user_func($callback);
		}
	}
}