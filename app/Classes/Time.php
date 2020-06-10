<?php

namespace App\Classes;

class Time{
	public static function toSeconds($timeOnly){
		return strtotime(sprintf('1970-01-01 %s UTC', $timeOnly));
	}

	public static function mysqlStamp(int $convert = 0){
		if ($convert == 0)
			return date('Y-m-d H:i:s');
		else
			return date('Y-m-d H:i:s', $convert);
	}

	public static function randomDate(string $start, int $daysToAdd){
		$min = strtotime($start);
		$max = $min + ($daysToAdd * 86400);
		$val = rand($min, $max);
		return self::mysqlStamp($val);
	}
}