<?php

namespace App\Library\Enums\Brands;

class Status extends \BenSampo\Enum\Enum
{
	const Pending = 'pending';

	const Approved = 'approved';

	const Rejected = 'rejected';
}