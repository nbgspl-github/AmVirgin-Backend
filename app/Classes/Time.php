<?php

namespace App\Classes;

class Time {
	public static function toSeconds($timeOnly) {
		return strtotime(sprintf('1970-01-01 %s UTC', $timeOnly));
	}
}