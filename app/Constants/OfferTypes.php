<?php

namespace App\Constants;

class OfferTypes {
	const FlatRate = 1;

	const Percentage = 2;

	public static function name($value = 0) {
		if ($value == 0)
			return 'no-discount';
		else if ($value == 1)
			return 'flat-rate';
		else if ($value == 2)
			return 'percent';
		else
			return 'unknown';
	}
}