<?php

namespace App\Queries\Seller;

use App\Models\SellerBankDetail;
use App\Queries\AbstractQuery;
use App\Queries\Traits\SellerAuthentication;

class BankDetailQuery extends AbstractQuery{
	use SellerAuthentication;

	protected function model(): string{
		return SellerBankDetail::class;
	}

	public static function begin(): self{
		return new static();
	}

	public function displayable(): self{
		return $this;
	}
}