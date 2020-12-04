<?php

namespace App\Enums\Orders\Returns;

use BenSampo\Enum\Enum;

class Status extends Enum
{
	const Pending = 'pending';
	const Approved = 'approved';
	const Disapproved = 'disapproved';
	const Processing = 'processing';
	const Completed = 'completed';
}