<?php

namespace App\Library\Enums\Advertisements;

use BenSampo\Enum\Enum;

class Status extends Enum
{
	const Pending = 'pending';
	const Approved = 'approved';
	const Disapproved = 'disapproved';
}