<?php

namespace App\Queries;

use App\Models\SellerBankDetail;
use App\Queries\Traits\SellerAuthentication;

class SellerBankDetailQuery extends AbstractQuery{
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