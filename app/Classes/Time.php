<?php

namespace App\Classes;

class Time {
	public static function toSeconds($timeOnly) {
		return strtotime(sprintf('1970-01-01 %s UTC', $timeOnly));
	}

	public static function mysqlStamp(int $convert = 0) {
		if ($convert == 0)
			return date('Y-m-d H:i:s');
		else
			return date('Y-m-d H:i:s', $convert);
	}
}