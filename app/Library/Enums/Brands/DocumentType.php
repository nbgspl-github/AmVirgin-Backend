<?php

namespace App\Library\Enums\Brands;

class DocumentType extends \BenSampo\Enum\Enum
{
	const TrademarkCertificate = 'trademark-certificate';

	const BrandAuthorizationLetter = 'brand-authorization-letter';

	const Invoice = 'invoice';

	const Other = 'other';
}