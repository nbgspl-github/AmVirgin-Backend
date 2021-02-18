<?php

namespace App\Queries\Seller;

use App\Models\SellerBankDetail;
use App\Queries\AbstractQuery;

class BankDetailQuery extends AbstractQuery
{
	protected function model () : string
	{
		return SellerBankDetail::class;
	}

	public static function begin () : self
	{
		return new static();
	}

	public function displayable () : self
	{
		return $this;
	}

	public function useAuth () : self
	{
		$this->query->where('sellerId', auth('seller-api')->id());
		return $this;
	}
}