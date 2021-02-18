<?php

namespace App\Library\Enums\Transactions;

use BenSampo\Enum\Enum;

class Status extends Enum
{
	const Created = 'created';
	const Attempted = 'attempted';
	const Paid = 'paid';
}