<?php

namespace App\Library\Enums\Orders\Payments;

use BenSampo\Enum\Enum;

class Methods extends Enum
{
	const Card = 'card';
	const NetBanking = 'net-banking';
	const Wallet = 'wallet';
	const Upi = 'upi';
	const CashOnDelivery = 'cash-on-delivery';
}