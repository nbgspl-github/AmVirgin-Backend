<?php

namespace App\Library\Enums\Cart;

use BenSampo\Enum\Enum;

class Status extends Enum
{
	const Pending = 'pending';
	const Submitted = 'submitted';
}