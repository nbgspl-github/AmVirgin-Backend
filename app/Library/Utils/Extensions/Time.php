<?php

namespace App\Library\Utils\Extensions;

class Time
{
	const MYSQL_FORMAT = "Y-m-d H:i:s";
	const BEGIN_OF_DAY = "00:00:00";
	const END_OF_DAY = "23:59:59";

	public static function toSeconds ($timeOnly)
	{
		return strtotime(sprintf('1970-01-01 %s UTC', $timeOnly));
	}

	public static function mysqlStamp (int $convert = 0)
	{
		if ($convert == 0)
			return date('Y-m-d H:i:s');
		else
			return date('Y-m-d H:i:s', $convert);
	}

	public static function randomDate (string $start, int $daysToAdd)
	{
		$min = strtotime($start);
		$max = $min + ($daysToAdd * 86400);
		$val = rand($min, $max);
		return self::mysqlStamp($val);
	}

	public static function toDuration ($value, string $format = "%02dh:%02dm:%02ds") : string
	{
		$hours = floor($value / 3600);
		$minutes = floor($value / 60 % 60);
		$seconds = intval($value % 60);
		return sprintf($format, $hours, $minutes, $seconds);
	}
}