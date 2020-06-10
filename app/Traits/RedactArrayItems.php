<?php

namespace App\Traits;

trait RedactArrayItems {
	/**
	 * Filters an array using the provided keys.
	 * @param $input
	 * @param $keys
	 * @return array
	 */
	private function redact($input, $keys) {
		if (($input != null && count($input) > 0) && ($keys != null && count($keys) > 0)) {
			return array_filter($input, function ($item) use ($keys) {
				return !array_key_exists($item, $keys);
			});
		}
		return $input;
	}
}