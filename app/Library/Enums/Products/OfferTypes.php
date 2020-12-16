<?php

namespace App\Library\Enums\Products;

use BenSampo\Enum\Enum;

class OfferTypes extends Enum
{
	const None = 0;
	const FlatRate = 1;
	const Percentage = 2;

	public static function name ($value = 0) : string
	{
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
