<?php

namespace App\Queries\Seller;

use App\Models\SellerBankDetail;
use App\Models\SellerBusinessDetail;
use App\Queries\AbstractQuery;
use App\Queries\Traits\SellerAuthentication;

class BusinessDetailQuery extends AbstractQuery{
	use SellerAuthentication;

	protected function model(): string{
		return SellerBusinessDetail::class;
	}

	public static function begin(): self{
		return new static();
	}

	public function displayable(): self{
		return $this;
	}
}