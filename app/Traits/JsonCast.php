<?php

namespace App\Traits;

trait JsonCast {
	/**
	 * Cast an attribute to a native PHP type.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return mixed
	 */
	protected function castAttribute($key, $value) {
		if (is_null($value)) {
			return $value;
		}

		switch ($this->getCastType($key)) {
			case 'jsonArray':
				return jsonDecodeArray($value);
				break;
			default:
				return parent::__castAttribute($key, $value);
		}
	}
}